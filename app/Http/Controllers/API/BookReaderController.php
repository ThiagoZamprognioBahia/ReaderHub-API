<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookReaderResource;
use App\Models\BookReader;
use Illuminate\Http\Request;

class BookReaderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        
        $bookReaders = BookReader::where('reader_id', $request->input('reader_id'))->paginate($perPage);

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
            return response()->json(['message' => 'Book is already added to your profile'], 404);
        }

        $bookReader = BookReader::create($validatedData);

        return response()->json(['message' => 'Book successfully added to reader profile', 'data' => $bookReader], 201);
    }

    public function destroy($id)
    {
        $bookReader = BookReader::find($id);
        
        $bookReader->delete();

        return response()->json([
            'message' => 'Book successfully deleted from your profile',
        ], 200); 
    }

}
