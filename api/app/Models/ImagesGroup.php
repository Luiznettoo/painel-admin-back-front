<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ImagesGroup extends Model
{
    public    $timestamps = false;
    protected $fillable   = ['identifier'];
    
    public function getRouteKeyName(): string {
        return 'identifier';
    }
    
    public function images(): BelongsToMany {
        return $this->belongsToMany(File::class);
    }
}