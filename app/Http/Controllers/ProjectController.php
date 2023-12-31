<?php

namespace App\Http\Controllers;

use App\Enums\BuildingClassification;
use App\Enums\BuildingType;
use App\Exceptions\PermissionException;
use App\Http\Resources\BuildingParametersReportResource;
use App\Http\Resources\BuildingsBySystemReportResource;
use App\Http\Resources\BuildingsByUserResource;
use App\Http\Resources\DesignReportResource;
use App\Models\Data;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        Role::enablePermission(Role::agent);

        $validator = Validator::make($request->all(), ['project_name' => 'required', 'owner_name' => 'required', 'owner_lastname' => 'required',
            'designer_name' => 'required', 'project_director' => 'required', 'department' => 'required',
            'municipality' => 'required', 'address' => 'required',
            'phone' => 'required',
            'profession' => 'required',
            'nationality' => 'required',
            'building_classification' => ['required', new Enum(BuildingClassification::class)],
            'building_type' => ['required', new Enum(BuildingType::class)]
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $project = Project::whereProjectName($request->project_name)->first();
        if ($project) return response()->json(['error' => 'Ya existe un proyecto con el mismo nombre'], 400);
        $response = Auth::user()->projects()->create($request->all());

        return response()->json(['data' => $response], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     *
     * @return Response
     */
    public function show(Project $project)
    {
        Role::canModify($project->user()->value('id')==\auth()->id() || auth()->user()->role_id==Role::supervisor);
        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     * @throws PermissionException
     */
    public function update(Request $request, Project $project)
    {
        Role::canModify($project->user()->value('id')==\auth()->id() || auth()->user()->role_id==Role::supervisor);
        $request->validate(['project_name' => 'required', 'owner_name' => 'required', 'owner_lastname' => 'required',
            'designer_name' => 'required', 'project_director' => 'required', 'department' => 'required',
            'municipality' => 'required', 'address' => 'required',
            'phone' => 'required',
            'profession' => 'required',
            'nationality' => 'required',
            'building_classification' => ['required', new Enum(BuildingClassification::class)],
            'building_type' => ['required', new Enum(BuildingType::class)]]);

        $project->update($request->all());

        return response()->json(['data' => $project]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     *
     * @return Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }

    public function getProjectData(Request $request)
    {

        $validator = Validator::make($request->all(), ['project_id' => 'required', 'key' => 'required|string']);
        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $result = Data::whereProjectId($request->project_id)->where(['key' => $request->key])->first();
        if ($result) $result->payload = json_decode($result->payload);
        return response()->json($result);

    }

    public function getAllProjectData(Request $request)
    {

        $validator = Validator::make($request->all(), ['project_id' => 'required']);
        if ($validator->fails()) return response()->json($validator->errors(), 400);
        $project = Project::find($request->project_id, ['id', 'building_classification']);
        foreach ($project->data as $value) {
            $value->payload = json_decode($value->payload);
        }
        return response()->json($project);

    }

    public function saveProjectData(Request $request)
    {
        $validator = Validator::make($request->all(), ['project_id' => 'required', 'key' => 'required|string', 'payload' => 'required']);
        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $result = Data::where(['key' => $request->key, 'project_id' => $request->project_id])->first();
        if (!$result) $result = Data::create($request->only('project_id', 'key'));

        $result->payload = json_encode($request->payload);
        $result->save();
        return response()->json($result);
    }

    public function saveFiles(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'project_file' => 'required|file|mimes:jpeg,png,gif,pdf|max:5120',
            'key' => 'required'],
            [
                'project_file.mimes' => 'El archivo debe ser una imagen (JPEG, PNG, GIF) o un archivo PDF.',
                'project_file.max' => 'El tamaño máximo permitido para el archivo es de 5MB.',
            ]);
        if ($validator->fails()) return response()->json($validator->errors(), 400);


        $file = $request->file('project_file');
        $key  = $request->key . '-file';

        $project = Project::find($request->project_id);

        if ($project) {
            $project->getMedia($key)->each(function ($media) {
                $media->delete();
            });
        }
        $result = $project->addMedia($file)->toMediaCollection($key);
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
        $user = \auth()->user();
        if ($user->role_id == Role::supervisor) {
            $query = Project::whereNot('status', Project::IN_PROGRESS);
            $query->search($request->value);
            return $this->orderFilters($request, $query);
        }

        $query = $user->projects()->search($request->value);
        return $this->orderFilters($request, $query);
    }

    /**
     * @param Request $request
     * @param         $query
     *
     * @return JsonResponse
     */
    public function orderFilters(Request $request, $query): JsonResponse
    {
        if ($request->id_filter === 'true') $query->orderBy('id', 'DESC');
        if ($request->created_at_filter === 'true') $query->orderBy('created_at', 'DESC');
        if ($request->updated_at_filter === 'true') $query->orderBy('updated_at', 'DESC');
        $result = $query->get()->all();

        return response()->json($result);
    }

    public function setStatus(Request $request)
    {
//  inProgress: 0,
//	inRevision: 1,
//	accepted: 2,
//	denied: 3,

        $validator = Validator::make($request->all(), ['project_id' => 'required', 'status' => 'required']);
        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $result              = Project::find($request->project_id);
        $result->status      = $request->status;
        $result->reviewer_id = auth()->user()->id;
        $result->save();
        return response()->json($result);
    }

    public function getStatus(Request $request)
    {

        $validator = Validator::make($request->all(), ['project_id' => 'required']);
        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $result = Project::find($request->project_id);
        return response()->json(['result' => (int)$result->status]);
    }

    public function report(Request $request)
    {

        Role::enablePermission(Role::supervisor);
        $validator = Validator::make($request->all(), ['type' => 'required']);
        if ($validator->fails()) return response()->json($validator->errors(), 400);

        $type   = $request->type;
        $year   = $request->year;
        $result = null;
        switch ($type) {
            case 'design-compliances':
                $approved         = Project::whereStatus(Project::APPROVED)->whereYear('created_at', $year)->get()->all();
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
                $denied = Project::whereStatus(Project::DENIED)->whereYear('created_at', $year)->get();
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
                $result = [
                    'approved' => [
                        'households' => $households,
                        'offices' => $offices,
                        'tertiary' => $tertiary,
                        'total' => count($approved)
                    ],
                    'denied' => [
                        'households' => $deniedHouseholds,
                        'offices' => $deniedOffices,
                        'tertiary' => $deniedTertiary,
                        'total' => count($denied)
                    ]
                ];
                break;
            case 'buildings-parameters': // p0043C

                $result = [
                    ['name' => 'Viviendas',
                        'type' => 'column',
                        'data' => Data::calculateDataByAverageType(BuildingClassification::households, $request)],
                    ['name' => 'Oficinas',
                        'type' => 'column',
                        'data' => Data::calculateDataByAverageType(BuildingClassification::offices, $request)],
                    ['name' => 'Terciarios',
                        'type' => 'column',
                        'data' => Data::calculateDataByAverageType(BuildingClassification::tertiary, $request)],
                ];
                break;

            case 'system-buildings':
                $year = $request->input('year'); // Obtener el año del request

                $result = Project::select('department', 'building_classification', DB::raw('count(*) as total_projects'))
                    ->whereYear('created_at', $year)
                    ->groupBy('building_classification', 'department')
                    ->withOut('data')
                    ->get();
                break;

            case 'user-buildings':
                $users  = User::whereNot('id', \auth()->user()->id)->where('role_id',Role::agent)->whereYear('created_at',$year)->withCount('projects')->orderBy('projects_count', 'desc')->limit(10)->get();
                $result = [
                    'users' => $users,
                    'total' => Project::all()->count()
                ];
                break;

        }

        return \response()->json($result);
    }

    public function reportExcel(Request $request)
    {
        Role::enablePermission(Role::supervisor);

        $type = $request->type;
        $year = $request->year;

        $result = ['message' => 'error type'];
        switch ($type) {
            case 'user-buildings':// p0043A
                $topUsers = User::withCount('projects') // Contar la cantidad de proyectos por usuarios
                ->where('role_id',Role::agent)
                ->orderByDesc('projects_count')
                    ->orderByDesc('created_at')
                    ->limit(10)
                    ->get();
                $result   = Project::whereIn('user_id', $topUsers->pluck('id'))->orderBy('user_id', 'DESC')->get();
                $result   = BuildingsByUserResource::collection($result)->toArray($request);
                break;
            case 'system-buildings':// p0043B
                $result = Project::whereYear('created_at', $year)->get();
                $result = BuildingsBySystemReportResource::collection($result)->toArray($request);
                break;
            case 'buildings-parameters': //p0043C
                $result = Project::where('status', Project::APPROVED)->whereYear('created_at', $year)->get();
                $result = BuildingParametersReportResource::collection($result)->toArray($request);
                break;
            case 'design-compliances':// p0043D
                $result = Project::where('status', Project::APPROVED)->orWhere('status', Project::DENIED)->whereYear('created_at', $year)->get();
                $result = DesignReportResource::collection($result)->toArray($request);
                break;
        }
        return $result;
    }
}
