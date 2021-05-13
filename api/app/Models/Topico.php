<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topico extends Model
{
    use SoftDeletes;

    protected $table = 'plans_topicos';
    protected $with = ['file'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'plan_id',
        'topico',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

}
