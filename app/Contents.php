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

    public function setValueParser($item, $parsers)
    {
        foreach($parsers as $parser){
            $this->add(['']);
            die();
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(\App\Params::class, 'param_id', 'id');
    }
}
