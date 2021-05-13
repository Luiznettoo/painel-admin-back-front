<?php
namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function get(Request $request, Testimonial $testimonial): JsonResponse{
        if($testimonial->id){
            $testimonial->load('file.resources');
            return response()->json($testimonial->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        $query = Testimonial::with('file.resources');

        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);
        $query->orderBy('id','desc');

        return response()->json($query->paginate($perPage, ['*'], 'page', $page));

    }
    public function post(Request $request):JsonResponse {

        $this->validateJSON([
            'name'             => 'required|string|min:3',
            'file_id'          => 'nullable|exists:files,id',
            'texto1'           => 'nullable',
            'texto2'           => 'nullable',
            'cargo'            => 'required|string|min:3',
            'titulo'           => 'required|string|min:3',
        ]);

        $fields = $request->json()->all();
        $testimonial = new Testimonial($fields);
        $testimonial->save();

        return response()->json($testimonial->toArray(),201);

    }

    public function put(Request $request,Testimonial $testimonial):JsonResponse {
        $this->validateJSON([
            'name'             => 'required|string|min:3',
            'file_id'          => 'nullable|exists:files,id',
            'texto1'           => 'nullable',
            'texto2'           => 'nullable',
            'cargo'            => 'required|string|min:3',
            'titulo'           => 'required|string|min:3',
        ]);

        $fields = $request->json()->all();
        $testimonial->fill($fields);
        $testimonial->save();
        return response()->json($testimonial->toArray(),204);
    }

    public function delete(Testimonial $testimonial){
        $testimonial->delete();
    }

}
