<?php

namespace App\Http\Controllers\API;

use App\Exceptions\InvalidIdException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBook;
use App\Http\Requests\UpdateBook;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Publisher;
use App\Services\Filters\BooksFilterService;
use App\Services\PublisherService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $publisherService;

    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }

    public function index(Request $request, BooksFilterService $booksFilterService)
    {
        $books = Book::query();

        // Apply filters using the BooksFilterService service
        $books = $booksFilterService
            ->apply($books, $request
                ->only(['name', 'genre', 'author', 'publisher_name', 'isbn']));

        if ($books->count() === 0) {
            return response()->json(['message' => 'No books found.'], 404);
        }

        // Retrieves books with filters applied
        $perPage = $request->input('per_page', 15);
        $books = $books->with('genre', 'publisher')->paginate($perPage);

        return BookResource::collection($books);
    }

    public function store(StoreBook $request)
    {
        $validatedData = $request->validated();

        //Checks if the ISBN already exists
        if (isset($validatedData['isbn'])) {
            $existingBook = Book::where('isbn', $validatedData['isbn'])->first();
            if ($existingBook) {
                return response()->json(['message' => 'A book with this ISBN already exists.'], 422);
            }
        }

        // Check if publisher ID was provided
        if (!isset($validatedData['publisher_id'])) {
            // Check if an publisher with the given name already exists
            $validatedData['publisher_name'] = preg_replace('/[^\p{L}\p{N}\s]/u', '', $validatedData['publisher_name']);
            $validatedData['publisher_name'] = ucfirst(mb_strtolower($validatedData['publisher_name'], 'UTF-8'));

            $existingPublisher = Publisher::where('name', $validatedData['publisher_name'])->first();

            if (!$existingPublisher) {
                // If an publisher with the given name does not exist, create a new publisher
                $publisherData = [
                    'name'      => $validatedData['publisher_name'],
                    'code'      => isset($validatedData['publisher_code']) ? $validatedData['publisher_code'] : null,
                    'telephone' => isset($validatedData['publisher_telephone']) ? $validatedData['publisher_telephone'] : null
                ];

                $publisher = $this->publisherService->store($publisherData);

                if (isset($publisher['error'])) {
                    return response()->json(['message' => $publisher['error']], 422);
                }

                // Update the publisher ID with the new created ID
                $validatedData['publisher_id'] = $publisher->id;
            } else {
                // If an publisher with the given name already exists, update the publisher ID
                $validatedData['publisher_id'] = $existingPublisher->id;
            }
        }

        // Check if the publisher with the given ID exists in the database
        $existingPublisher = Publisher::find($validatedData['publisher_id']);

        if (!$existingPublisher) {

            throw new InvalidIdException("The provided publisher was not found.", $validatedData['publisher_id']);
        }

        // Check if the genre ID was provided
        if (!isset($validatedData['genre_id'])) {
            // Prepare the genre name
            $genreName = ucwords(strtolower(trim($validatedData['genre_name'])));

            // Find or create the genre
            $genre = Genre::firstOrCreate(['name' => $genreName]);

            // Update the genre ID with the new or existing ID
            $validatedData['genre_id'] = $genre->id;
        }

        $book = Book::create($validatedData);

        return response()->json([
            'message' => 'Book created successfully',
            'data' =>  BookResource::make($book),
        ], 201);
    }

    public function show($id)
    {
        $book = Book::with('genre', 'publisher')->find($id);

        // Check if the book exists
        if (!$book) {
            throw new InvalidIdException("Book not found.", $id );
        }

        return BookResource::make($book);
    }

    public function update(UpdateBook $request, $id)
    {
        $validatedData = $request->validated();

        $book = Book::find($id);

        // Check if the book exists
        if (!$book) {
            throw new InvalidIdException("Book not found.", $id );
        }

        // Checks if the given ISBN already exists for another book
        if (isset($validatedData['isbn']) && $validatedData['isbn'] !== $book->isbn) {
            $existingBook = Book::where('isbn', $validatedData['isbn'])->first();
            if ($existingBook) {
                return response()->json(['message' => 'A book with this ISBN already exists.'], 422);
            }
        }

        // Checks if publisher ID was provided
        if (!isset($validatedData['publisher_id']) && isset($validatedData['publisher_name'])) {

            // Checks if an publisher with the given name already exists
            $validatedData['publisher_name'] = preg_replace('/[^\p{L}\p{N}\s]/u', '', $validatedData['publisher_name']);
            $validatedData['publisher_name'] = ucfirst(mb_strtolower($validatedData['publisher_name'], 'UTF-8'));

            $existingPublisher = Publisher::where('name', $validatedData['publisher_name'])->first();

            if (!$existingPublisher) {
                // If an publisher with the given name does not exist, create a new publisher
                $publisherData = [
                    'name'      => $validatedData['publisher_name'],
                    'code'      => isset($validatedData['publisher_code']) ? $validatedData['publisher_code'] : null,
                    'telephone' => isset($validatedData['publisher_telephone']) ? $validatedData['publisher_telephone'] : null
                ];

                $publisher = $this->publisherService->store($publisherData);

                if (isset($publisher['error'])) {
                    return response()->json(['message' => $publisher['error']], 422);
                }

                $validatedData['publisher_id'] = $publisher->id;
            } else {
                // If an publisher with the given name already exists, update the publisher ID
                $validatedData['publisher_id'] = $existingPublisher->id;
            }
        }

        // Checks if the genre ID was provided
        if (!isset($validatedData['genre_id']) && isset($validatedData['genre_name'])) {

            $genreName = ucwords(strtolower(trim($validatedData['genre_name'])));

            $genre = Genre::firstOrCreate(['name' => $genreName]);

            $validatedData['genre_id'] = $genre->id;
        }

        $book->update($validatedData);

        return response()->json([
            'message' => 'Book updated successfully',
            'data' => BookResource::make($book),
        ], 200);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        // Check if the book exists
        if (!$book) {
            throw new InvalidIdException("Book not found.", $id );
        }

        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully',
        ], 200);
    }
}
