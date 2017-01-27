<?php


namespace Understand\UnderstandLumen;

use Illuminate\Support\Facades\Mail;
use Monolog\Logger;

class UnderstandEmailHandler extends \UnderstandMonolog\Handler\UnderstandBaseHandler
{

    protected $from;

    protected $to;

    public function __construct($from, $to, $inputToken, $apiUrl = 'https://api.understand.io', $silent = true, $sslBundlePath = false, $level = Logger::DEBUG, $bubble = true)
    {
        $this->from = $from;
        $this->to = $to;

        parent::__construct($inputToken, $apiUrl, $silent, $sslBundlePath, $level, $bubble);
    }


    /**
     * Send data to storage
     *
     * @param string $data
     * @return string
     */
    protected function send($data)
    {
        $params = json_decode($data, true);

        try {
            Mail::raw(view('mail.exception', $params), function ($msg) {
                $msg->to([$this->to]);
                $msg->from([$this->from]);
            });
        } catch (\Exception $e) {

        }
    }
}