<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReader;
use App\Http\Resources\ReaderResource;
use App\Models\Reader;
use Illuminate\Http\Request;


class ReaderController extends Controller
{
    public function show($id)
    {
        $reader = Reader::findOrFail($id);

        return ReaderResource::make($reader);
    }

    public function update($id, UpdateReader $request)
    {
        $reader = Reader::findOrFail($id);
        $reader->update($request->validated());

        return response()->json([
            'message' => 'Leitor atualizado com sucesso',
            'dados'   => $reader,
        ], 200); 
    }

    public function destroy(Request $request, $id)
    {
        $validatedData = $request->validate([
            'password' => 'required|string',
        ]);

        $reader = Reader::findOrFail($id);

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
}