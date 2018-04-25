<?php

namespace App;


use App\Http\Traits\MailTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Params extends Model
{
    use MailTrait;

    protected $fillable = [
        'name',
        'value',
        'cron'
    ];

    protected $table = "params";

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function setCronAttribute($value)
    {
        $this->attributes['cron'] = ($value == null)?0:1;
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contents() {
        return $this->hasMany(\App\Contents::class, 'param_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'user_id', 'id');
    }

    public function saveContents($parsers = null)
    {
        if(!$parsers)return null;
        foreach($parsers as $key => $parser){
            if(\App\Contents::where('key', $key)->orWhere('url', $parser)->count())continue;
            $content = new \App\Contents();
            $content->key = $key;
            $content->url = $parser;
            $this->setElement($content);
            $this->contents()->save($content);
        }
        return true;
    }

    /**
     *
     */
    public static function boot() {
        static::creating(function ($item) {
          if(!$item->user_id){
              $item->user_id = Auth::id();
          }
        });
    }
}
