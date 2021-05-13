<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryAcabamento extends Model
{
    use SoftDeletes;
    protected $table = "category_acabament";
    protected $with = ['file'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name',
        'ordem',
        'text',
        'category_id',
        'file_id',
    ];

    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function acabamento(): HasMany {
        return $this->hasMany(Acabamento::class,'category_id','id')->orderBy('ordem')->orderBy('id');
    }

}