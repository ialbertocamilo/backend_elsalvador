<?php

namespace App\Http\Controllers;

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
        return response()->json(['message'=>'No se pudo actualizar, el usuario no existe.'],400);
    }

    public function changeRole(Request $request){

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
        return response()->json(['message'=>'No se pudo actualizar, el usuario no existe.'],400);
    }
}
