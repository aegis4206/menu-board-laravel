<?php

namespace App\Models;

use App\Models\BaseModel;

class Type extends BaseModel
{
    //
    protected $fillable = ['sort', 'name', 'imgurl'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function types()
    {
        return $this->hasMany(Type::class);
    }
}
