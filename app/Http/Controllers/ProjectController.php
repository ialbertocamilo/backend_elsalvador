<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Project;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        $user     = \auth()->user();

        if ($user->role_id == Role::supervisor) {
            $projects = Project::whereNot('status', Project::IN_PROGRESS)->get();
            return response()->json($projects);
        }

        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Role::enablePermission(Role::agent);
        $request->validate([
            'project_name' => 'required',
            'owner_name' => 'required',
            'owner_lastname' => 'required',
            'designer_name' => 'required',
            'project_director' => 'required',
            'department' => 'required',
            'municipality' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'profession' => 'required',
            'nationality' => 'required',
        ]);
        $project = Project::whereProjectName($request->project_name)->first();
        if ($project)
            return response()->json(['error' => 'Ya existe un proyecto con el mismo nombre'], 400);
        $response = Auth::user()->projects()->create($request->all());

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Project $project)
    {

        $request->validate([
            'project_name' => 'required',
            'owner_name' => 'required',
            'owner_lastname' => 'required',
            'designer_name' => 'required',
            'project_director' => 'required',
            'department' => 'required',
            'municipality' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'profession' => 'required',
            'nationality' => 'required',
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
        if (!$result)
            $result = Data::create($request->only('project_id', 'key'));

        $result->payload = json_encode($request->payload);
        $result->save();
        return response()->json($result);
    }

    public function saveFiles(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'project_file' => 'required|file',
            'key' => 'required'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);


        $file = $request->file('project_file');
        $key  = $request->key . '-file';

        $project = Project::find($request->project_id);

        if ($project) {
            $project->getMedia($key)->each(function ($media) {
                $media->delete();
            });
        }
        $result = $project->addMedia($file)
            ->toMediaCollection($key);
        return response()->json($result);
    }


    public function getFiles(Request $request)
    {
        $key = $request->key . '-file';

        $data = Project::find($request->project_id)->getMedia($key);
        return response()->json($data);
    }

    public function downloadFile(Request $request)
    {
        $file = Media::findByUuid($request->id)->first();
        return response()->download($file->getPath());
    }

    public function search(Request $request)
    {
        $user=\auth()->user();
        if ($user->role_id==Role::supervisor){
            $result = Project::whereNot('status', Project::IN_PROGRESS)->search($request->value)->get()->all();
            return response()->json($result);
        }
        $result = $user->projects()->search($request->value)->get()->all();
        return response()->json($result);
    }

    public function setStatus(Request $request)
    {
//  inProgress: 0,
//	inRevision: 1,
//	accepted: 2,
//	denied: 3,

        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'status' => 'required'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);

        $result         = Project::find($request->project_id);
        $result->status = $request->status;
        $result->save();
        return response()->json($result);
    }

    public function getStatus(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'project_id' => 'required'
        ]);
        if ($validator->fails())
            return response()->json($validator->errors(), 400);

        $result = Project::find($request->project_id);
        return response()->json(['result' => (int)$result->status]);
    }

    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), ['type' => 'required']);
        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $type   = $request->type;
        $result = null;
        switch ($type) {
            case 'design-compliances':
                $approved         = Project::whereStatus(Project::APPROVED)->get()->all();
                $households       = 0;
                $deniedHouseholds = 0;
                $offices          = 0;
                $deniedOffices    = 0;
                $tertiary         = 0;
                $deniedTertiary   = 0;
                foreach ($approved as $value) {
                    switch ($value->building_classification) {
                        case BuildingClassification::households->value:
                            $households++;
                            break;
                        case BuildingClassification::offices->value:
                            $offices++;
                            break;
                        case BuildingClassification::tertiary->value:
                            $tertiary++;
                            break;
                    }
                }
                $denied = Project::whereStatus(Project::DENIED)->get();
                foreach ($denied as $value) {
                    switch ($value->building_classification) {
                        case BuildingClassification::households->value:
                            $deniedHouseholds++;
                            break;
                        case BuildingClassification::offices->value:
                            $deniedOffices++;
                            break;
                        case BuildingClassification::tertiary->value:
                            $deniedTertiary++;
                            break;
                    }
                }
                $result = ['approved' => ['households' => $households, 'offices' => $offices, 'tertiary' => $tertiary, 'total' => count($approved)], 'denied' => ['households' => $deniedHouseholds, 'offices' => $deniedOffices, 'tertiary' => $deniedTertiary, 'total' => count($denied)]

                ];
                break;
            case 'buildings-parameters':

                break;

            case 'system-buildings':
                $year                                    = $request->year;
                $result = Project::select(
                    DB::raw('YEAR(created_at) as year'),
                    'department',
                    'building_classification',
                    DB::raw('
                CASE
                    WHEN building_classification = 0 THEN "Viviendas"
                    WHEN building_classification = 1 THEN "Oficinas"
                    WHEN building_classification = 2 THEN "Terciarios"
                    ELSE "Otro"
                END as classification'),
                    DB::raw('COUNT(*) as project_count')
                )
                    ->when($year, function ($query) use ($year) {
                        return $query->whereYear('created_at', $year);
                    })
                    ->groupBy('year', 'department', 'classification','building_classification')
                    ->orderBy('year', 'asc')
                    ->orderBy('department', 'asc')
                    ->orderBy('classification', 'asc')
                    ->get();
                break;

            case 'user-buildings':
                $users  = User::whereNot('id', \auth()->user()->id)->withCount('projects')->orderBy('projects_count', 'desc')->limit(10)->get();
                $result = ['users' => $users, 'total' => Project::all()->count()];
                break;

        }

        return \response()->json($result);
    }
}
