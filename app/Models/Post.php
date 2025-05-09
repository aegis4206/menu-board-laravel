<?php

namespace App\Models;

use App\Models\BaseModel;

class Post extends BaseModel
{
    //
    protected $fillable = ['sort', 'type_id', 'tab_id', 'title', 'content', 'imgurl'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
