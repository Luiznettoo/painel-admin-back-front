<?php
namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Models\TextBanner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TextBannerController extends Controller
{
    public function get(Request $request, TextBanner $textBanner): JsonResponse{
        if($textBanner->id){
            $textBanner->load('file.resources');
            return response()->json($textBanner->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        $query = TextBanner::with('file.resources');

        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);
        $query->orderBy('id','desc');

        return response()->json($query->paginate($perPage, ['*'], 'page', $page));

    }
    public function post(Request $request):JsonResponse {

        $this->validateJSON([
            'name'             => 'required|string|min:3',
            'text'             => 'nullable',
            'file_id'          => 'nullable|exists:files,id',
        ]);

        $fields = $request->json()->all();
        $textBanner = new TextBanner($fields);
        $textBanner->save();

        return response()->json($textBanner->toArray(),201);

    }

    public function put(Request $request,TextBanner $textBanner):JsonResponse {
        $this->validateJSON([
            'name'             => 'required|string|min:3',
            'text'             => 'nullable',
            'file_id'          => 'nullable|exists:files,id',
        ]);

        $fields = $request->json()->all();
        $textBanner->fill($fields);
        $textBanner->save();
        return response()->json($textBanner->toArray(),204);
    }

    public function delete(TextBanner $textBanner){
        $textBanner->delete();
    }

}
