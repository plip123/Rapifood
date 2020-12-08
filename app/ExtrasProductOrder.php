<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtrasProductOrder extends Model
{
    protected $table = "extras_product_orders";
    public $primaryKey = "id";
    public $timestamps = true;
}
