<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\CategoriaBlog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaBlogController extends Controller
{
    public function get(Request $request, CategoriaBlog $categoriaBlog): JsonResponse {
        if ($categoriaBlog->id) {
            $categoriaBlog->load('blog.file.resources', 'file.resources');
            
            return response()->json($categoriaBlog->toArray());
        }
        $request->validate([
            'per-page' => 'nullable|integer',
            'page'     => 'nullable|integer'
        ]);
        
        $query = CategoriaBlog::with('blog.file.resources', 'file.resources');
        
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
            'color' => 'required',
        ]);
        
        $categoriaBlog = new CategoriaBlog();
        
        $categoriaBlog->fill($request->json()->all());
        
        $categoriaBlog->save();
        
        return response()->json($categoriaBlog->toArray(), 201);
    }
    
    public function put(Request $request, CategoriaBlog $categoriaBlog) {
        $this->validateJSON([
            'name'  => 'required|min:3',
            'color' => 'required',
        ]);
        $categoriaBlog->fill($request->json()->all());
        
        $categoriaBlog->save();
        
        return response()->json($categoriaBlog->toArray());
        
    }
    
    public function delete(CategoriaBlog $blog) {
    }
}