<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {

        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            Log::debug("authenticated user -> ".$user);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(['token' => $token, 'name'=>$user->name, 'id'=>$user->id, 'email'=>$user->email], 200);
        }
            return response()->json(['message' => 'Invalid credentials'], 401);

    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function verify(Request $request){
        $user = $request->user();

        return response()->json(['user' => $user], 200);
    }

    public function test(Request $request)
    {


        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        return response()->json(['token' => $credentials], 200);
    }
    public function register(Request $request)
    {
        // Validar los datos del formulario

        $validator=\Illuminate\Support\Facades\Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()){
            return response()->json($validator->messages());
        }
        // Crear un nuevo usuario
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        // Responder con JSON
        return response()->json(['message' => 'Registro exitoso'], 201);
    }
}
