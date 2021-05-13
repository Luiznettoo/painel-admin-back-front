<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int folder
 * @property int size
 * @property string|null mime
 * @property string name
 * @property string uuid
 * @property mixed file_id
 * @property string|null title
 * @property string|null alt
 */
class File extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['file_id'];
    protected $appends  = ['permalink'];
    
    public function childs(): HasMany {
        return $this->hasMany(self::class);
    }
    
    public function resources(): HasMany {
        return $this->hasMany(FileImageResource::class);
    }
    
    public function getPermalinkAttribute(): string {
        return config('app.url') . 'api/v1/storage/' . self::encrypt($this->id);
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
