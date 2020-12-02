<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $table = "product";
    public $primaryKey = "id";
    public $timestamps = true;
    protected $appends = ['image_url', 'link'];

    public function getImageUrlAttribute(){
        return Storage::url($this->image);
    }

    public function getLinkAttribute(){
        return "/combo" + Str::slug($this->name) + "/" + $this->id;
    }
}
