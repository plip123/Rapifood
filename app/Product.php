<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $table = "product";
    public $primaryKey = "id";
    public $timestamps = true;
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(){
        return Storage::url($this->image);
    }
}
