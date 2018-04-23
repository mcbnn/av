<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contents extends Model
{
    protected $table = "contents";

    protected $fillable = [
        'url',
        'key',
        'param_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
