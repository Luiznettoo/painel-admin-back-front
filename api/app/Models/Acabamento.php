<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acabamento extends Model
{
    use SoftDeletes;
    
    protected $table    = 'acabament';
    protected $with     = ['file', 'categoria'];
    protected $hidden   = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name',
        'text',
        'ordem',
        'ativo',
        'category_id',
        'file_id',
        'url',
    ];
    
    public function file(): BelongsTo {
        return $this->belongsTo(File::class);
    }
    
    public function categoria(): BelongsTo {
        return $this->belongsTo(CategoryAcabamento::class, 'category_id')->orderBy('ordem')->orderBy('id');
    }
}