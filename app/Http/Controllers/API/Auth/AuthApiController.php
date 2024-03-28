<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    public function auth(Request $request)
    {

        $validatedData = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $reader = Reader::where('email', $validatedData['email'])->first();

        // Check if the reader exists and the password is correct
        if (!$reader || !Hash::check($validatedData['password'], $reader->password)) {
            throw ValidationException::withMessages([
                'message' => ['senha ou email incorretos'],
            ]);
        }

        // Revoke all of the reader's tokens
        $reader->tokens()->delete();

        // Create a new access token
        $token = $reader->createToken('TokenName')->plainTextToken;

        return response()->json([
            'message'   => 'Autenticação bem-sucedida',
            'token'     => $token,
        ]);
    }
}
