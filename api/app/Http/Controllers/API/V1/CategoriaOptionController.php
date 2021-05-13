<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\CategoriaOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaOptionController extends Controller
{
    public function get(Request $request, CategoriaOption $categoriaOption): JsonResponse {
        if ($categoriaOption->id) {
            $categoriaOption->load('options');
            return response()->json($categoriaOption->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        
        $query = CategoriaOption::query();
        $query->orderBy('ordem');
        $query->orderBy('id');
        
        $query->with('options');
        
        $perPage = $request->get('per-page', NULL);
        $page    = $request->get('page', 1);
        
        if (isset($perPage)) {
            return response()->json($query->paginate($perPage, ['*'], 'page', $page));
        }
        return response()->json($query->get()->toArray());
        
    }
    
    public function post(Request $request) {
        $this->validateJSON([
            'name'  => 'required|min:3',
            'ordem' => 'nullable|integer',
        ]);
        
        $categoriaOption = new CategoriaOption();
        
        $categoriaOption->fill($request->json()->all());
        
        $categoriaOption->save();
        
        return response()->json($categoriaOption->toArray(), 201);
    }
    
    public function put(Request $request, CategoriaOption $categoriaOption) {
        $this->validateJSON([
            'name'  => 'required|min:3',
            'ordem' => 'nullable|integer',
        ]);
        $categoriaOption->fill($request->json()->all());
        
        $categoriaOption->save();
        
        return response()->json($categoriaOption->toArray());
        
    }
    
    public function delete(CategoriaOption $categoriaOption) {
        if ($categoriaOption->id) {
            $categoriaOption->delete();
        }
    }
    
    
}