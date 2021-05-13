<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;

class Category extends Model
{
    use SoftDeletes;
	
	protected $table = "category_product";
    protected $with = ['file','banner'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'ordem',
        'name',
        'file_id',
        'banner_id',
        'url',
        'ativo',
    ];

    public function getRouteKeyName(): string {
        $identifier = Route::current()->parameters()['categoria'];

        if (!ctype_digit($identifier)) {
            return 'friendly_url';
        }

        return 'id';
    }

    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function banner(): BelongsTo {
        return $this->belongsTo(File::class);
    }

	public function product(): BelongsToMany {
		return $this->BelongsToMany(Product::class,'product_categorys',NULL,'product_id');
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
