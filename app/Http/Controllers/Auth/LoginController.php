<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    use HttpResponses;
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                // Gera um token
                $token = $request->user()->createToken('token_simple');

                return $this->response('Autorizado',201,['token' => $token, 'name' => $user->name]);
            }

            throw ValidationException::withMessages(['email' => 'Credenciais inválidas']);

        } catch (ValidationException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
}
