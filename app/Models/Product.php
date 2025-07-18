<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['user_id', 'category_id', 'name', 'price', 'unit'];

    function category()
    {
        return $this->belongsTo((Category::class));
    }
}
