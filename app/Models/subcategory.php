<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subcategory extends Model
{
    use HasFactory;

    protected $table = 'subcategorys';

    protected $fillable = [
     'name',
     'slug',
     'image',
     'status',
     'showhome',
     'category_id'
    ];
}
