<?php


namespace Understand\UnderstandLumen;

use Illuminate\Support\Facades\Mail;

class UnderstandEmailHandler extends \UnderstandMonolog\Handler\UnderstandBaseHandler
{

    /**
     * Send data to storage
     *
     * @param string $data
     * @return string
     */
    protected function send($data)
    {
        Mail::raw(view('mail.exception', $data), function($msg) {
            $msg->to(['mark.okeeffe@digistorm.com.au']);
            $msg->from(['mark.okeeffe@digistorm.com.au']);
        });
    }
}