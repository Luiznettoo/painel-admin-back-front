<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\QueryException;

class Blog extends Model
{
    use SoftDeletes;



    protected $table = "blog";
    protected $with = ['file','banner','capa'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'title',
        'autor',
        'text',
        'body',
        'file_id',
        'banner_id',
        'capa_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'category_id',
        'url',
    ];

    public function getRouteKeyName(): string {
        $identifier = Route::current()->parameters()['blog'];

        if (!ctype_digit($identifier)) {
            return 'url';
        }

        return 'id';
    }

    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function banner(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function capa(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function categoria(): BelongsTo {
        return $this->belongsTo(CategoriaBlog::class,'category_id','id');
    }

    public function setFriendlyURL(string $url): void {
        try {
            $this->friendly_url = $url;
            $this->save();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] === 1062) { // Unique constraint failed...
                $buffer = [];
                preg_match('/-(\d+)$/', $url, $buffer);

                if (isset($buffer[1])) {
                    $this->setFriendlyURL(preg_replace('/-\d+$/', NULL, $url) . '-' . (((int)$buffer[1]) + 1));
                }
                else {
                    $this->setFriendlyURL($url . '-1');
                }
            }
            else {
                throw $e;
            }
        }
    }
}
