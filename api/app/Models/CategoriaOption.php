<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Route;

class CategoriaOption extends Model
{
    use SoftDeletes;

    protected $table = "category_option";
    protected $with = ['options.file.resources'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name',
        'ordem',
    ];

    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function options(): HasMany {
        return $this->hasMany(Option::class,'category_id','id')->orderBy('ordem')->orderBy('id');
    }

    public function product(): BelongsToMany {
        return $this->BelongsToMany(Product::class,'product_options',NULL,'product_id');
    }

}
