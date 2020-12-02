<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = "stores";
    public $primaryKey = "id";
    public $timestamps = true;
}
