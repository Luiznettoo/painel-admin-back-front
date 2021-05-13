<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int file_id
 * @property string uuid
 * @property string extension
 * @property int width
 */
class FileImageResource extends Model
{
    public $timestamps = false;
    protected $fillable = ['file_id', 'uuid', 'extension', 'width'];
    protected $appends = ['permalink'];
    
    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }
    
    public function getPermalinkAttribute(): string {
        return config('app.url') . 'api/v1/storage/resources/' . self::encrypt($this->id);
    }
    
    protected static function encrypt($data): string {
        $encrypted = NULL;
        $iv = NULL;
        
        do {
            $encMethod = config('app.openssl_enc');
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encMethod));
            
            $encrypted = openssl_encrypt($data, $encMethod, config('app.openssl_key'), OPENSSL_RAW_DATA, $iv);
        } while (strpos("$encrypted::$iv", ':::'));
        
        return urlencode(self::base64url_encode("$encrypted::$iv"));
    }
    
    protected static function base64url_encode($data): string {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
