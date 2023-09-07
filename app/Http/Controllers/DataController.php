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
        $request->get('project_id');

        $response = Auth::user()->data()->create($request->all());

        return response()->json(['data' => $response], 201);
    }


    public function show(string $project_id, Request $data)
    {
        $data->validate([
            'payload' => 'required',
            'project_id' => 'required',
            'designer_name' => 'required',
            'project_director' => 'required'
        ]);
        $response = Project::find($project_id)->data()->create($data);
        return response()->json(['data' => $response], 200);
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



}
