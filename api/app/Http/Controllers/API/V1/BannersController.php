<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\ImagesGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    public function get(ImagesGroup $identifier) {
        if (!$identifier->id) {
            return response()->json(ImagesGroup::with('images.resources')->get()->toArray());
        }
        
        return response()->json($identifier->load('images.resources')->toArray());
    }
    
    public function list(Request $request, ImagesGroup $identifier) {
        $perPage = $request->get('per-page') ?? 20;
        $page = $request->get('page') ?? 1;
        
        return response()->json($identifier->images()->paginate($perPage, ['*'], 'page', $page)->toArray());
    }
    
    public function patch(Request $request): JsonResponse {
        $banners = $request->json()->all();
        
        foreach ($banners as $identifier => $bannersIDs) {
            if (!$identifier) {
                continue;
            }
            
            $banner = ImagesGroup::firstOrCreate(['identifier' => $identifier]);
            
            if ($banner) {
                $banner->images()->sync($bannersIDs);
            }
        }
        
        return response()->json();
    }
}