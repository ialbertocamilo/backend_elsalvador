<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::paginate(15);

        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required',
            'owner_name' => 'required',
            'designer_name' => 'required',
            'project_director' => 'required'
        ]);
        $response = Auth::user()->project()->create($request->all());

        return response()->json(['data' => $response], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project $project
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Project      $project
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {

        $request->validate([
            'project_name' => 'required',
            'owner_name' => 'required',
            'designer_name' => 'required',
            'project_director' => 'required'
        ]);

        $project->update($request->all());

        return response()->json(['data' => $project]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Project $project
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }

    public function getProjectData(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'key' => 'required|string'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);

        $result = Data::whereProjectId($request->project_id)->where(['key' => $request->key])->first();
        if ($result)
            $result->payload = json_decode($result->payload);
        return response()->json($result);

    }

    public function saveProjectData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'key' => 'required|string',
            'payload' => 'required'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);

        $result = Data::where(['key' => $request->key, 'project_id' => $request->project_id])->first();
        if (!$result) {
            $result = Data::create($request->only('project_id', 'key'));
        }
        $result->payload = json_encode($request->payload);
        $result->save();
        return response()->json($result);
    }
}
