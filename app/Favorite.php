<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = "favorites";
    public $primaryKey = "id";
    public $timestamps = true;
}
