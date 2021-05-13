<?php


namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Models\MyEvents;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MyEventsController extends Controller
{
    public function get(Request $request, MyEvents $myEvents):JsonResponse {
        if($myEvents->id){
            return response()->json($myEvents->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        $query = MyEvents::query();
        $this->filterResults($request,$query);

        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);

        return response()->json($query->paginate($perPage, ['*'], 'page', $page));
    }

    public function post(Request $request):JsonResponse {

        $this->validateJSON([
            'title'             => 'required|string|min:3',
            'tipo'              => 'required|string',
            'data'              => 'required|date'
        ]);

        $fields = $request->json()->all();
        $myEvents = new MyEvents($fields);
        $myEvents->save();


        return response()->json($myEvents->toArray(),201);

    }

    public function put(Request $request,MyEvents $myEvents):JsonResponse {
        return response()->json($myEvents->toArray(),204);
    }

    private function filterResults(\http\Env\Request $request, Builder $query): void {
        $title        = $request->get('title' ,0);
        $tipo        = $request->get('tipo' ,0);
        $data        = $request->get('data' ,0);

        $this->verificador($title,'title',$query);
        $this->verificador($tipo,'tipo',$query);
        $this->verificador($data,'data',$query);

    }

    private function verificador($var1,$var2,$query){
        if ($var1) {
            $query->where($var2, '=', $var1);
        }
    }


    public function delete():JsonResponse {

    }
}
