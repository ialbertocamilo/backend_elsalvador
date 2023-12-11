<?php

namespace App\Http\Controllers;

use App\Helpers\RoleCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if ($validator->fails()) return response()->json(['errors' => $validator->errors()->all()], 400);

        if (Auth::attempt(['email' => $request->username, 'password' => $request->password])) {
            $user  = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            if (!$user->active)
                return response()->json(['message' => 'El usuario no está activo.'], 400);
            return response()->json(['token' => $token,
                'name' => $user->name,
                'lastname' => $user->lastname,
                'id' => $user->id,
                'role' => $user->role_id,
                'email' => $user->email]);
        }
        return response()->json(['message' => 'Credenciales inválidas'], 401);

    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function verify(Request $request)
    {
        $user = $request->user();

        $auth=Str::remove('Bearer ',$request->header('authorization'));
        return response()->json(['token' =>$auth,
            'name' => $user->name,
            'lastname' => $user->lastname,
            'id' => $user->id,
            'role' => $user->role_id,
            'email' => $user->email]);
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

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        // Crear un nuevo usuario

        $user               = new User();
        $user->name         = $request->name;
        $user->lastname     = $request->lastname;
        $user->profession   = $request->profession;
        $user->nationality  = $request->nationality;
        $user->department   = $request->department;
        $user->municipality = $request->municipality;
        $user->address      = $request->address;
        $user->phone        = $request->phone;
        $user->email        = $request->email;
        $user->password     = password_hash($request->password, PASSWORD_BCRYPT);
        $user->role_id      = Role::agent;

        $user->save();

        // Responder con JSON
        return response()->json(['message' => 'Registro exitoso', 'data' => $user->name . ' ' . $user->lastname], 201);
    }
}
