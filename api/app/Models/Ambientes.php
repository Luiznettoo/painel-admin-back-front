<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;

class Ambientes extends Model
{
    use SoftDeletes;

    protected $table = 'category_ambience';
    protected $with = ['file.resources'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'ordem',
        'name',
        'file_id',
        'url',
        'order',
    ];

    public function getRouteKeyName(): string {
        $identifier = Route::current()->parameters()['ambiente'];

        if (!ctype_digit($identifier)) {
            return 'friendly_url';
        }

        return 'id';
    }

    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function product(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'product_ambientes','ambiente_id','product_id');
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