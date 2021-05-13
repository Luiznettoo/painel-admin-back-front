<?php
namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function get(Request $request, Team $team): JsonResponse{
        if($team->id){
            $team->load('file.resources');
            return response()->json($team->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        $query = Team::with('file.resources');

        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);
        $query->orderBy('id','desc');

        return response()->json($query->paginate($perPage, ['*'], 'page', $page));

    }
    public function post(Request $request):JsonResponse {

        $this->validateJSON([
            'name'             => 'required|string|min:3',
            'file_id'          => 'nullable|exists:files,id',
            'texto'            => 'nullable',
            'cro'              => 'required|string|min:3',
        ]);

        $fields = $request->json()->all();
        $team = new Team($fields);
        $team->save();

        return response()->json($team->toArray(),201);

    }

    public function put(Request $request,Team $team):JsonResponse {
        $this->validateJSON([
            'name'             => 'required|string|min:3',
            'file_id'          => 'nullable|exists:files,id',
            'texto'            => 'nullable',
            'cro'              => 'required|string|min:3',
        ]);

        $fields = $request->json()->all();
        $team->fill($fields);
        $team->save();
        return response()->json($team->toArray(),204);
    }

    public function delete(Team $team){
        $team->delete();
    }

}
