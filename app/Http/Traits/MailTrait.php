<?php

namespace App\Http\Traits;

use Mail;

trait MailTrait {

    public static $el = [];

    public function setElement($el)
    {
        MailTrait::$el[$el->key] = $el->url;
    }

    public function deleteElement($key)
    {
        if(isset(MailTrait::$el[$key]))unset(MailTrait::$el[$key]);
    }

    public function sendMail($param)
    {
        if(!MailTrait::$el)return;
        Mail::send('emails.parser', ['data' =>  MailTrait::$el], function ($message) use ($param) {
            $message->from('mcbnn123@gmail.com', date('d.m h:i:s').' '.$param->name);
            $message->to($param->user->email);
        });
    }
}