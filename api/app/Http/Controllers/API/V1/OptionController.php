<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OptionController extends Controller
{
    
    public function get(Request $request, Option $option): JsonResponse {
        if ($option->id) {
            $option->load('file.resources');
            return response()->json($option->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        
        $query = Option::query();
        $query->orderBy('ordem');
        $query->orderBy('id');
        $this->filterResults($request, $query);
        
        $query->with('categoria');
        
        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);
        
        if ($perPage) {
            return response()->json($query->paginate($perPage, ['*'], 'page', $page));
        } else {
            return response()->json($query->get()->all());
        }
    }
    
    public function post(Request $request): JsonResponse {
        
        $this->validateJSON([
            'name'    => 'required|string|max:191',
            'ordem'   => 'nullable|integer',
            'file_id' => 'nullable|exists:files,id',
        ]);
        
        $fields = $request->json()->all();
        $option = new Option($fields);
        $option->save();
        
        
        return response()->json($option->toArray(), 201);
        
    }
    
    public function put(Request $request, Option $option): JsonResponse {
        $fields = $request->json()->all();
        $option->fill($fields);
        $option->save();
        return response()->json($option->toArray(), 204);
        
    }
    
    public function delete(Option $option): Response {
        $option->delete();
        return response(NULL, 204);
    }
    
    private function filterResults(Request $request, Builder $query): void {
        $title = $request->get('name', 0);
        
        $this->verificador($title, 'name', $query);
    }
    
    private function verificador($var1, $var2, $query) {
        if ($var1) {
            $query->where($var2, '=', $var1);
        }
    }
    
}