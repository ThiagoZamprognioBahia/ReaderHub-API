<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookReaderResource;
use App\Models\Book;
use App\Models\BookReader;
use App\Models\Reader;
use Illuminate\Http\Request;

class BookReaderController extends Controller
{
    public function index(Request $request)
    {
        $bookReaders = BookReader::where('reader_id', $request['reader_id'])->get();

        return BookReaderResource::collection($bookReaders);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'reader_id' => 'required|exists:readers,id',
            'book_id'   => 'required|exists:books,id',
        ]);

        $BookAlreadyRead = BookReader::where('reader_id', $validatedData['reader_id'])
        ->where('book_id', $validatedData['book_id'])
        ->exists();

        if ($BookAlreadyRead) {
            return response()->json(['message' => 'Livro já está adicionado ao seu perfil'], 404);
        }

        $bookReader = BookReader::create($validatedData);

        $reader = Reader::findOrFail($validatedData['reader_id']);

        $book = Book::findOrFail($validatedData['book_id']);

        // Update the reader's total books and pages read
        $reader->total_books_read += 1; // Increases the total number of books read
        $reader->total_pages_read += $book->pages; // Adds the number of pages in the book read
        $reader->save();

        return response()->json(['message' => 'Livro adicionado ao perfil do leitor com sucesso', 'data' => $bookReader], 201);
    }

    public function destroy($id)
    {
        $bookReader = BookReader::find($id);
        
        $bookReader->delete();

        $reader = Reader::findOrFail($bookReader['reader_id']);
        $book = Book::findOrFail($bookReader['book_id']);
        
        // Update the reader's total books and pages read
        $reader->total_books_read -= 1; // Increases the total number of books read
        $reader->total_pages_read -= $book->pages; // Adds the number of pages in the book read

        return response()->json([
            'message' => 'Livro excluido com sucesso do seu perfil',
        ], 200); 
    }

}
