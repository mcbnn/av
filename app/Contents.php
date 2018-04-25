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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(\App\Params::class, 'param_id', 'id');
    }

    /**
     *
     */
    public static function boot() {
        static::creating(function ($item) {
            $count = \App\Contents::where('param_id', $item->param_id)->count();
            $params =  new \App\Params();
            $param = $params->find($item->param_id);
            $param->count = $count;
            $param->update();
        });
    }
}
