<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Group extends Model{

    protected $table = 'group';
    protected $hidden = ['created_at', 'updated_at'];
    protected $fillable = [
        'name',
        'product_id',
    ];

    public function options(): BelongsToMany {
        return $this->BelongsToMany(Option::class,'group_option',NULL,'option_id');
    }

}