<?php


namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MyEvents extends Authenticatable{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'tipo',
        'data'
    ];

}
