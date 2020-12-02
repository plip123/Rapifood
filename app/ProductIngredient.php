<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductIngredient extends Model
{
    protected $table = "product_ingredients";
    public $primaryKey = "id";
    public $timestamps = true;
}
