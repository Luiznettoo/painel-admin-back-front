<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $table = 'plans';
    protected $with = ['file'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'title',
        'description',
        'price',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function topicos(): HasMany {
        return $this->hasMany(Topico::class);
    }
}
