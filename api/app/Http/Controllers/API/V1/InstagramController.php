<?php
namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Models\Instagram;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstagramController extends Controller
{
    public function get(Request $request, Instagram $instagram): JsonResponse{
        if($instagram->id){
            $instagram->load('file.resources');
            return response()->json($instagram->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        $query = Instagram::with('file.resources');

        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);
        $query->orderBy('id','desc');

        return response()->json($query->paginate($perPage, ['*'], 'page', $page));

    }
    public function post(Request $request):JsonResponse {

        $this->validateJSON([
            'name'             => 'required|string|min:3',
            'file_id'          => 'nullable|exists:files,id',
            'url'              => 'required|string|min:3',
        ]);

        $fields = $request->json()->all();
        $instagram = new Instagram($fields);
        $instagram->save();

        return response()->json($instagram->toArray(),201);

    }

    public function put(Request $request,Instagram $instagram):JsonResponse {
        $this->validateJSON([
            'name'             => 'required|string|min:3',
            'file_id'          => 'nullable|exists:files,id',
            'url'              => 'required|string|min:3',
        ]);

        $fields = $request->json()->all();
        $instagram->fill($fields);
        $instagram->save();
        return response()->json($instagram->toArray(),204);
    }

    public function delete(Instagram $instagram){
        $instagram->delete();
    }

}