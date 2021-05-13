<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderProduct extends Model
{

    protected $table = 'order_products';
    protected $hidden = ['created_at', 'updated_at'];
    protected $with = [
        'products',
        'orderOptions',
//        'options'
    ];
    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'quantity',
    ];

    public function products(): BelongsTo{
        return $this->belongsTo(Product::class,'product_id','id');
    }

//    public function options(): BelongsToMany{
//        return $this->belongsToMany(OrderOptions::class,'order_options');
//    }

    public function orderOptions(): HasMany{
        return $this->hasMany(OrderOptions::class,'order_id','order_id');
    }


    public function options(): BelongsToMany {
        return $this->belongsToMany(Option::class,'order_options','order_id','option_id')->withTrashed()->withPivot('name');
    }



}