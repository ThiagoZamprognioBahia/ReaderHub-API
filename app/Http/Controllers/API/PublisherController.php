<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Publisher;
use App\Services\PublisherService;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    protected $publisherService;

    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $publishers = Publisher::paginate($perPage);

        return response()->json([
            'data'   => $publishers,
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'      => 'required|string',
            'code'      => 'nullable|string',
            'telephone' => 'nullable|string',
        ]);

        $validatedData['name'] = preg_replace('/[^\p{L}\p{N}\s]/u', '', $validatedData['name']);
        $validatedData['name'] = ucfirst(mb_strtolower($validatedData['name'], 'UTF-8'));


        // Call the service to update the publisher
        $result = $this->publisherService->store($validatedData);

        // Check if there were any errors
        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], 422);
        }

        return response()->json([
            'message' => 'Editora cadastrada com sucesso',
            'data'   => $result,
        ], 200);
    }

    public function show($id)
    {
        $publisher = Publisher::findOrFail($id);

        return response()->json([
            'data'   => $publisher,
        ], 200);
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'name'      => 'nullable|string',
            'code'      => 'nullable|string',
            'telephone' => 'nullable|string',
        ]);

        // Call the service to update the publisher
        $result = $this->publisherService->update($id, $validatedData);

        // Check if there were any errors
        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], 422);
        }

        return response()->json([
            'message' => 'Editora atualizada com sucesso',
            'data'   => $result,
        ], 200);
    }

    public function destroy($id)
    {
        // Find the publisher by ID
        $publisher = Publisher::findOrFail($id);

        // Check if there are any books associated with this publisher
        $associatedBooks = Book::where('publisher_id', $id)->exists();

        // If there are associated books, return a message indicating that the publisher cannot be deleted
        if ($associatedBooks) {
            $books = Book::where('publisher_id', $id)->pluck('name')->toArray();
            return response()->json([
                'message' => 'Não é possível excluir a editora. Os seguintes livros estão associados a esta editora e devem ser editados primeiro:',
                'books' => $books
            ], 422);
        }

        // If there are no associated books, proceed with deleting the publisher
        $publisher->delete();

        return response()->json([
            'message' => 'Editora excluída com sucesso',
        ], 200); 
    }
}
