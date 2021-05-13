<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Acabamento;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcabamentoController extends Controller
{
    public function get(Request $request, Acabamento $acabamento): JsonResponse {
        if ($acabamento->id) {
            return response()->json($acabamento->toArray());
        }
        
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        $query = Acabamento::with('file.resources');
        $query->orderBy('ordem');
        $query->orderBy('id');
        
        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);
        
        $categorias = $request->get('categoria', null);
        
        if ($categorias) {
            $query->where('category_id', '=', $categorias);
            $query->where('ativo', '=', '1');
        }
        
        return response()->json($query->paginate($perPage, ['*'], 'page', $page));
    }
    
    public function post(Request $request): JsonResponse {
        $this->validateJSON([
            'name'        => 'required|min:3',
            'ordem'       => 'nullable|integer',
            'text'        => 'nullable',
            'category_id' => 'nullable',
            'ativo'       => 'nullable',
            'file_id'     => 'nullable|exists:files,id',
        ]);
        
        $fields   = $request->json()->all();
        $myEvents = new Acabamento($fields);
        $myEvents->save();
        
        return response()->json($myEvents->toArray(), 201);
        
    }
    
    public function put(Request $request, Acabamento $acabamento): JsonResponse {
        $this->validateJSON([
            'name'        => 'required|min:3',
            'ordem'       => 'nullable|integer',
            'text'        => 'nullable',
            'ativo'  => 'nullable',
            'category_id' => 'nullable',
            'file_id'     => 'nullable|exists:files,id',
        ]);
        $acabamento->fill($request->json()->all());
        
        $acabamento->save();
        return response()->json($acabamento->toArray(), 200);
    }
    
    public function delete(Acabamento $acabamento): JsonResponse {
        $acabamento->delete();
        
        return response()->json($acabamento->toArray(), 204);
    }
}
