<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductOrders extends Model
{
    protected $table = "product_orders";
    public $primaryKey = "id";
    public $timestamps = true;
}
