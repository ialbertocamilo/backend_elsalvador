<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function getAll()
    {
        $myId  = auth()->user()->id;
        $users = User::whereNot('id', $myId)->with('role')->get();
        return response()->json($users);
    }

    public function getOne(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);


        $user = User::find($request->user_id);
        return response()->json($user);
    }

    public function changeActive(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'active' => 'required',
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);

        $user = User::whereId($request->user_id)->first();
        if ($user) {
            $user->active = $request->active;
            $user->save();
            return response()->json(true);
        }
        return response()->json(['message' => 'No se pudo actualizar, el usuario no existe.'], 400);
    }

    public function changeRole(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'role_id' => 'required',
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);

        $user = User::whereId($request->user_id)->first();
        if ($user) {
            $user->role_id = $request->role_id;
            $user->save();
            return response()->json(true);
        }
        return response()->json(['message' => 'No se pudo actualizar, el usuario no existe.'], 400);
    }

    public function updateUser(Request $request)
    {
        Role::enablePermission(Role::supervisor);
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }
        // Crear un nuevo usuario

        $user               = User::find($request->id);
        $user->name         = $request->name;
        $user->lastname     = $request->lastname;
        $user->profession   = $request->profession;
        $user->nationality  = $request->nationality;
        $user->department   = $request->department;
        $user->municipality = $request->municipality;
        $user->address      = $request->address;
        $user->phone        = $request->phone;
        $user->email        = $request->email;
        $user->role_id      = $request->role_id;
        $user->update();

        // Responder con JSON
        return response()->json(['message' => 'Registro exitoso', 'data' => $user->name . ' ' . $user->lastname], 201);
    }
}
