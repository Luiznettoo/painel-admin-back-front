<?php
namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Models\Seo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function get(Request $request, Seo $seo): JsonResponse{
        if($seo->id){
            $seo->load('file.resources');
            return response()->json($seo->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        $query = Seo::with('file.resources');

        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);
        $query->orderBy('id','desc');

        return response()->json($query->paginate($perPage, ['*'], 'page', $page));

    }
    public function post(Request $request):JsonResponse {

        $this->validateJSON([
            'name'             => 'required|string|min:3',
            'meta_title'       => 'required|min:3',
            'meta_description' => 'nullable',
            'meta_keywords'    => 'nullable',
        ]);

        $fields = $request->json()->all();
        $seo = new Seo($fields);
        $seo->save();

        return response()->json($seo->toArray(),201);

    }

    public function put(Request $request,Seo $seo):JsonResponse {
        $this->validateJSON([
            'name'             => 'required|string|min:3',
            'meta_title'       => 'required|min:3',
            'meta_description' => 'nullable',
            'meta_keywords'    => 'nullable',
        ]);

        $fields = $request->json()->all();
        $seo->fill($fields);
        $seo->save();
        return response()->json($seo->toArray(),204);
    }

    public function delete(Seo $seo){
        $seo->delete();
    }

}
