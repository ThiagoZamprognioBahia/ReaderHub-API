<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::all();

        return response()->json([
            'data'   => $genres,
        ], 200);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        $name = preg_replace('/[^\p{L}\p{N}\s]/u', '', $validatedData['name']);

        $name = ucfirst(mb_strtolower($name, 'UTF-8'));

        // Check if a record with the same name already exists
        if (Genre::where('name', $name)->exists()) {
            return response()->json(['message' => 'A record with that name already exists.'], 422);
        }

        try {
            DB::beginTransaction();

            $genre = Genre::create([
                'name' => $name,
            ]);  

            return response()->json([
                'message' => 'Gênero criado com sucesso',
                'data'    => $genre,
            ], 201); 
        } catch (\Exception $e) {
            return response()->json(['message' => 'Successfully created genre'], 500);
        }

    }

    public function show($id)
    {
        $genre = Genre::findOrFail($id);

        return response()->json([
            'data'   => $genre,
        ], 200);
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        $genre = Genre::findOrFail($id);
        $genre->update($validatedData);

        return response()->json([
            'message' => 'Genre updated successfully',
            'data'   => $genre,
        ], 200); 
    }

    public function destroy($id)
    {
        // Find the genre by ID
        $genre = Genre::findOrFail($id);

        // Check if there are any books associated with this genre
        $associatedBooks = Book::where('genre_id', $id)->exists();

        // If there are associated books, return a message indicating that the genre cannot be deleted
        if ($associatedBooks) {
            $books = Book::where('genre_id', $id)->pluck('name')->toArray();
            return response()->json([
                'message' => 'Cannot delete gender. The following books are associated with this genre and should be edited first:',
                'books' => $books
            ], 422);
        }

        // If there are no associated books, proceed with deleting the genre
        $genre->delete();

        return response()->json([
            'message' => 'Successfully deleted genre',
        ], 200); 
    }
}
