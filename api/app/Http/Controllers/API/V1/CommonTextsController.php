<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\CommonText;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonTextsController extends Controller
{
    public function get(CommonText $identifier) {
        if (!$identifier->id) {
            return response()->json(CommonText::get()->toArray());
        }
        
        return response()->json($identifier->toArray());
    }
    
    public function patch(Request $request): JsonResponse {
        $texts = $request->json()->all();
        
        foreach ($texts as $identifier => $content) {
            if (!$content) {
                continue;
            }
            
            $text = CommonText::firstOrNew([
                'identifier' => $identifier
            ]);
            
            if ($text) {
                $text->content = $content;
    
                $text->save();
            }
        }
        
        return response()->json();
    }
}