<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model{
    use SoftDeletes;

    protected $table = 'option';
    protected $with = ['file'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name',
        'file_id',
        'ordem',
        'category_id',
    ];

    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }

    public function categoria(): BelongsTo {
        return $this->belongsTo(CategoriaOption::class,'category_id','id')->orderBy('ordem')->orderBy('id');
    }
}