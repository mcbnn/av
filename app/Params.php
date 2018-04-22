<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Params extends Model
{
    protected $fillable = [
        'name',
        'value'
    ];

    protected $table = "params";

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public static function boot() {
        static::creating(function ($item) {
          if(!$item->user_id){
              $item->user_id = Auth::id();
          }
        });
    }
}
