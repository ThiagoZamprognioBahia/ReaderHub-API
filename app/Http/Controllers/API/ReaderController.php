<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReader;
use App\Http\Resources\ReaderResource;
use App\Models\Reader;
use App\Services\Filters\ReadersFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ReaderController extends Controller
{

    public function index(Request $request, ReadersFilterService $readersFilterService)
    {
        $readers = Reader::query();
        
        // Apply filters using the BooksFilterService service
        $readers = $readersFilterService->apply($readers, $request->only(['name', 'last_name', 'email', 'telephone', 'birthday']));

        $perPage = $request->input('per_page', 10);
        $readers = $readers->paginate($perPage);

        return response()->json([
            'data'   => $readers,
        ], 200);
    }
    public function show($id)
    {
        $reader = Reader::find($id);

        if (!$reader) {
            return response()->json(['message' => 'Leitor não encontrado.'], 404);
        }

        Redis::set('total_books_read:' . $reader->id, $reader->total_books_read);
        Redis::set('total_pages_read:' . $reader->id, $reader->total_pages_read);

        return ReaderResource::make($reader);
    }

    public function update($id, UpdateReader $request)
    {
        $reader = Reader::find($id);

        if (!$reader) {
            return response()->json(['message' => 'Leitor não encontrado.'], 404);
        }

        $reader->update($request->validated());

        return response()->json([
            'message' => 'Leitor atualizado com sucesso',
            'data'    => ReaderResource::make($reader),
        ], 200); 
    }

    public function destroy(Request $request, $id)
    {
        $validatedData = $request->validate([
            'password' => 'required|string',
        ]);

        $reader = Reader::find($id);

        if (!$reader) {
            return response()->json(['message' => 'Leitor não encontrado.'], 404);
        }

        if (!Hash::check($validatedData['password'], $reader->password)) {
            throw ValidationException::withMessages([
                'password' => ['senha incorreta'],
            ])->status(422); 
        }

        $reader->delete();

        return response()->json([
            'message' => 'Leitor excluído com sucesso',
        ], 204); 
    }

    // Function that returns the total number of pages read and the total number of books read
    public function getTotalBooksAndPagesFromCache($id)
    {
        $totalBooksRead = Redis::get('total_books_read:' . $id);
        $totalPagesRead = Redis::get('total_pages_read:' . $id);

        return [
            'total_books_read' => $totalBooksRead,
            'total_pages_read' => $totalPagesRead
        ];
    }
}