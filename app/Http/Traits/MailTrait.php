<?php

namespace App\Http\Traits;

use Mail;

trait MailTrait {

    public $el = [];

    public function setElement($el)
    {
        $this->el[] = $el;
    }

    public function sendMail($param)
    {
        if(!$this->el)return;
        Mail::send('emails.parser', ['data' => $this->el], function ($message) use ($param) {
            $message->from('mcbnn123@gmail.com', 'avito '.date('d.m h:i:s').' '.$param->name);
            $message->to($param->user->name);
        });
    }
}