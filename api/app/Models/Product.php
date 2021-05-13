<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;

class Product extends Model
{
    use SoftDeletes;
    protected $table = 'product';
    protected $with = ['images.resources'];
    protected $fillable = [
        'ordem',
        'name',
        'price',
        'promotion',
        'ref',
        'title',
        'description',
        'ordem',
        'url',
        'friendly_url',
        'file_id',
        'video_id',
        'thumb_id',
        'desta',
        'destc',
        'tendencia',
        'ativo',
    ];

    public function getRouteKeyName(): string {
        $identifier = Route::current()->parameters()['product'];

        if (!ctype_digit($identifier)) {
            return 'friendly_url';
        }

        return 'id';
    }

    public function products(): BelongsToMany {
        return $this->belongsToMany( Product::class, 'product_product',NULL,'product_son_id');
    }

    public function ambientes(): BelongsToMany {
        return $this->belongsToMany( Ambientes::class, 'product_ambientes',NULL,'ambiente_id');
    }

    public function categorys(): BelongsToMany {
        return $this->belongsToMany( Category::class, 'product_categorys',NULL,'category_id');
    }


    public function categoria_option(): BelongsToMany {
        return $this->belongsToMany( CategoriaOption::class, 'product_options',	'product_id','category_id')->orderBy('ordem')->orderBy('id');
    }


    public function images(): BelongsToMany {
        return $this->belongsToMany(File::class, 'product_images', NULL, 'image_id');
    }

    public function thumb(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function video(): BelongsTo {
        return $this->belongsTo(File::class);
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
