<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'payload' => 'required'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);

        $response = Data::savePackageConfiguration($request->key, $request->payload);
        return response()->json(['data' => $response], 201);
    }


    public function show(string $project_id, Request $data)
    {
        return response()->json(['data' => $project_id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Data $data)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Data $data)
    {
        //
    }

    public function getBy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);


        $response = Data::where(['key' => $request->key])->first();
        return response()->json(['data' => $response]);
    }





}
