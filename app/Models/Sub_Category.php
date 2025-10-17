<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sub_Category extends Model
{
    protected $fillable = ['name', 'slug', 'status', 'category_id', 'showHome'];

}
