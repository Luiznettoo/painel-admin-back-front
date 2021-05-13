<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model{
    use SoftDeletes;

    protected $table = 'order';
    protected $hidden = [ 'updated_at', 'deleted_at'];
    protected $with = ['orderProducts'];
    protected $fillable = [
        'name',
        'email',
        'assunto',
    ];

    public function orderProducts(): HasMany {
        return $this->hasMany(OrderProduct::class);
    }
/*
    public function products(): BelongsToMany {
        return $this->belongsToMany(OrderProduct::class,'order_products','order_id','product_id')->withPivot('name');
    }
/*
    public function options(): BelongsToMany {
        return $this->belongsToMany(Option::class,'order_options','order_id','option_id')->withPivot('name');
    }
*/

}