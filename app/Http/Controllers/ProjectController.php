<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $response=Auth::user()->project()->create($request->all());

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
            'email' => 'email',
            'membership_date' => 'date',
            'project_name' => 'required',
            'owner_name' => 'required',
            'designer_name' => 'required',
            'project_director' => 'required',
            'address' => 'required',
            'city' => 'required',
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
}
