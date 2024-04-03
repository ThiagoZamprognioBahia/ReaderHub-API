<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReader;
use App\Models\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PublicReaderController extends Controller
{
    public function store(StoreReader $request)
    {
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $reader           = Reader::make($validatedData);
            $reader->password = Hash::make($validatedData['password']);
            $reader->save();

            $token = $reader->createToken('TokenName')->plainTextToken;

            DB::commit();
            return response()->json([
                'message' => 'Reader created successfully',
                'token'   => $token,
                'data'    => $reader,
            ], 201); 
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating reader'], 500);
        }
    }
}
