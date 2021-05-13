<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CommonText extends Model
{
    public $timestamps  = false;
    protected $table = 'common_text';
    protected $fillable = [
        'identifier'
    ];
}