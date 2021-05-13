<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\FileImageResource;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    public function index(string $permalink, int $download = 0) {
        if (!$permalink) {
            return response('', 404);
        }
        
        $permalinkBuffer = explode('/', $permalink);
        $permalink       = $permalinkBuffer[0];
        
        $id = $this->decrypt($permalink);
        
        $attachment = File::withTrashed()->findOrFail($id);
        
        if (!$download) {
            if (strpos($attachment->mime, 'image/') === 0) {
                $optimized = '';
                $name      = $attachment->name;
                $mime      = $attachment->mime;
                
                if ($attachment->optimized) {
                    $optimized = 'optimized/';
                    $mime      = 'image/webp';
                    $name      = preg_replace('/\.[\da-z]+/i', '.webp', $attachment->name);
                }
                
                return response()->make(Storage::disk('local')->get("files/$optimized$attachment->uuid"), 200, [
                    'Content-Type'        => $mime,
                    'Content-Disposition' => "filename=\"$name\"",
                ]);
            }
            
            if (strpos($attachment->mime, 'application/pdf') === 0) {
                return response()->make(Storage::disk('local')->get("files/$attachment->uuid"), 200, [
                    'Content-Type'        => $attachment->mime,
                    'Content-Disposition' => "filename=\"$attachment->name\"",
                ]);
            }
        }
        
        return response()->download(Storage::disk('local')->path("files/$attachment->uuid"), $attachment->name);
    }
    
    public function resource(string $permalink, int $download = 0) {
        if (!$permalink) {
            return response('', 404);
        }
        
        $id = $this->decrypt($permalink);
        
        $resource = FileImageResource::with('file')->findOrFail($id);
        
        if (!$download) {
            return response()->make(Storage::disk('local')->get("files/resources/{$resource->uuid}"), 200, [
                'Content-Type'        => "image/{$resource->extension}",
                'Content-Disposition' => "filename=\"{$resource->file->name}\"",
            ]);
        }
        
        return response()->download(Storage::disk('local')->path("files/resources/{$resource->uuid}"), $resource->file->name);
    }
    
    protected function decrypt($data) {
        $rawData = $this->base64url_decode($data);
        $buffer  = explode('::', $rawData);
        
        [
            $ecrypted,
            $iv
        ] = $buffer;
        
        return openssl_decrypt($ecrypted, config('app.openssl_enc'), config('app.openssl_key'), OPENSSL_RAW_DATA, $iv);
    }
    
    protected function base64url_decode($data) {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
