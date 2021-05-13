<?php
namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Models\Covenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CovenantController extends Controller
{
    public function get(Request $request, Covenant $covenant): JsonResponse{
        if($covenant->id){
            $covenant->load('file.resources');
            return response()->json($covenant->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        $query = Covenant::with('file.resources');

        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);
        $query->orderBy('id','desc');

        return response()->json($query->paginate($perPage, ['*'], 'page', $page));

    }
    public function post(Request $request):JsonResponse {

        $this->validateJSON([
            'titulo'           => 'required|string|min:3',
            'link'             => 'required|string|min:3',
            'file_id'          => 'nullable|exists:files,id',
        ]);

        $fields = $request->json()->all();
        $covenant = new Covenant($fields);
        $covenant->save();

        return response()->json($covenant->toArray(),201);

    }

    public function put(Request $request,Covenant $covenant):JsonResponse {
        $this->validateJSON([
            'titulo'           => 'required|string|min:3',
            'link'             => 'required|string|min:3',
            'file_id'          => 'nullable|exists:files,id',
        ]);

        $fields = $request->json()->all();
        $covenant->fill($fields);
        $covenant->save();
        return response()->json($covenant->toArray(),204);
    }

    public function delete(Covenant $covenant){
        $covenant->delete();
    }

}
