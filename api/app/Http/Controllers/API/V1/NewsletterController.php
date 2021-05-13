<?php
namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function get(Request $request,Newsletter $newsletter):JsonResponse{
        if($newsletter->id){
            return response()->json($newsletter->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);

        $query = Newsletter::query();

        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);

        if(isset($perPage)) {
            return response()->json($query->paginate($perPage, ['*'], 'page', $page));
        }
        return response()->json($query->get()->toArray());
    }
    public function post(Request $request): JsonResponse{
        $this->validateJSON([
            'nome' => 'required|string',
            'email' => 'required|string',
        ]);

        $fields = $request->json()->all();
        $newsletter = new Newsletter($fields);
        $newsletter->save();
        return response()->json($newsletter->toArray(), 201);
    }

}
