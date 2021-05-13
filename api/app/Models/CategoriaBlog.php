<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaBlog extends Model{
    use SoftDeletes;

    protected $table = 'category_blog';
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name',
        'color'
    ];

    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function blog(): HasMany {
        return $this->hasMany(Blog::class,'category_id','id');
    }
}