<?php

namespace App\Models;

use App\Models\BaseModel;

class Tab extends BaseModel
{
    //
    protected $fillable = ['type_id', 'name'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
