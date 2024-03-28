<?php

namespace App\Http\Controllers\API;

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
        $books = $booksFilterService->apply($books, $request->only(['name', 'genre', 'author', 'publisher_name', 'isbn']));

        // Retrieves books with filters applied
        $books = $books->with('genre', 'publisher')->get();

        return BookResource::collection($books);
    }

    public function store(StoreBook $request)
    {
        $validatedData = $request->validated();

        //Checks if the ISBN already exists
        if (isset($validatedData['isbn'])) {
            $existingBook = Book::where('isbn', $validatedData['isbn'])->first();
            if ($existingBook) {
                return response()->json(['message' => 'Já existe um livro com este ISBN.'], 422);
            }
        }

        // Check if publisher ID was provided
        if (!isset($validatedData['publisher_id'])) {
            // Check if an editor with the given name already exists
            $validatedData['publisher_name'] = preg_replace('/[^\p{L}\p{N}\s]/u', '', $validatedData['name']);
            $validatedData['publisher_name'] = ucfirst(mb_strtolower($validatedData['publisher_name'], 'UTF-8'));

            $existingPublisher = Publisher::where('name', $validatedData['publisher_name'])->first(); 
        
            if (!$existingPublisher) {
                // If an editor with the given name does not exist, create a new editor
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
                // If an editor with the given name already exists, update the editor ID
                $validatedData['publisher_id'] = $existingPublisher->id;
            }
        } 

        // Check if the publisher with the given ID exists in the database
        $existingPublisher = Publisher::find($validatedData['publisher_id']);

        if (!$existingPublisher) {
            return response()->json(['message' => 'O ID do editor fornecido não foi encontrado.'], 422);
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
            'message' => 'Livro criado com sucesso',
            'data' =>  BookResource::make($book),
        ], 201);
    }

    public function show($id)
    {
        $book = Book::with('genre', 'publisher')->find($id);
        
        // Check if the book exists
        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado.'], 404);
        }

        return BookResource::make($book);
    }

    public function update(UpdateBook $request, $id)
    {
        $validatedData = $request->validated();

        $book = Book::find($id);

        // Check if the book exists
        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado.'], 404);
        }

        // Checks if the given ISBN already exists for another book
        if (isset($validatedData['isbn']) && $validatedData['isbn'] !== $book->isbn) {
            $existingBook = Book::where('isbn', $validatedData['isbn'])->first();
            if ($existingBook) {
                return response()->json(['message' => 'Já existe um livro com este ISBN.'], 422);
            }
        }

        // Checks if publisher ID was provided
        if (!isset($validatedData['publisher_id'])) {
            // Checks if an editor with the given name already exists
            $validatedData['publisher_name'] = preg_replace('/[^\p{L}\p{N}\s]/u', '', $validatedData['name']);
            $validatedData['publisher_name'] = ucfirst(mb_strtolower($validatedData['publisher_name'], 'UTF-8'));

            $existingPublisher = Publisher::where('name', $validatedData['publisher_name'])->first(); 
            
            if (!$existingPublisher) {      
                // If an editor with the given name does not exist, create a new editor
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
                // If an editor with the given name already exists, update the editor ID
                $validatedData['publisher_id'] = $existingPublisher->id;
            }
        } 
        
        // Checks if the genre ID was provided
        if (!isset($validatedData['genre_id'])) {
            
            $genreName = ucwords(strtolower(trim($validatedData['genre_name'])));

            $genre = Genre::firstOrCreate(['name' => $genreName]);

            $validatedData['genre_id'] = $genre->id;
        }

        $book->update($validatedData);

        return response()->json([
            'message' => 'Livro atualizado com sucesso',
            'data' => BookResource::make($book),
        ], 200);
    }

    public function destroy($id)
    {
        $book = Book::find($id);

        // Check if the book exists
        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado.'], 404);
        }
        
        $book->delete();

        return response()->json([
            'message' => 'Livro excluido com sucesso',
        ], 200); 
    }

}
