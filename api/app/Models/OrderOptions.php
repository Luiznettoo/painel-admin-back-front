<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderOptions extends Model
{
    protected $table = 'order_options';
    protected $hidden = ['created_at', 'updated_at'];
    protected $with = ['options'];
    protected $fillable = [
        'product_id',
        'name',
    ];

    public function options(): HasMany{
        return $this->hasMany(Option::class, 'id','option_id')->orderBy('ordem')->orderBy('id');
    }

}