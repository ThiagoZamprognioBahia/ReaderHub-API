<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
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
                'message' => ['incorrect password or email'],
            ]);
        }

        // Revoke all of the reader's tokens
        $reader->tokens()->delete();

        // Create a new access token
        $token = $reader->createToken('TokenName')->plainTextToken;

        return response()->json([
            'message'   => 'Successful authentication',
            'token'     => $token,
        ]);
    }
}
