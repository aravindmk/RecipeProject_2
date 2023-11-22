<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes'; // Adjust the table name as needed

    protected $fillable = ['food', 'description', 'image_url'];
}
