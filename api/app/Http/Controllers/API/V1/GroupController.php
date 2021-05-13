<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController  extends Controller{

    public function get(Request $request,Group $group): JsonResponse{
        if($group->id){
            $group->load('options');
            return response()->json($group->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);

        $query = Group::query();
        $this->filterResults($request,$query);

        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);

        return response()->json($query->paginate($perPage, ['*'], 'page', $page));
    }

    public function post(Request $request): JsonResponse{

        $fields = $request->json()->all();
        $group = new Group($fields);
        $group->save();


        return response()->json($group->toArray(),201);

    }
    public function put(Request $request,Group $group):JsonResponse{
        return response()->json($group->toArray(),204);

    }
    public function delete(Group $group){
        $group->delete();
    }

    private function filterResults(Request $request, Builder $query): void {
        $title        = $request->get('title' ,0);

        $this->verificador($title,'title',$query);

    }

    private function verificador($var1,$var2,$query){
        if ($var1) {
            $query->where($var2, '=', $var1);
        }
    }

}