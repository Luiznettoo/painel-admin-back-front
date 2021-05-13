<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seo extends Model
{
    use SoftDeletes;

    protected $table = 'seos';
    protected $with = ['file'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'name',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
