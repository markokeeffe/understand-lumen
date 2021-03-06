<?php


namespace Understand\UnderstandLumen;

use Illuminate\Support\Facades\Mail;
use Monolog\Logger;

class UnderstandEmailHandler extends \UnderstandMonolog\Handler\UnderstandBaseHandler
{

    protected $subjectPrefix;

    protected $from;

    protected $to;

    public function __construct($subjectPrefix, $from, $to, $inputToken, $apiUrl = 'https://api.understand.io', $silent = true, $sslBundlePath = false, $level = Logger::DEBUG, $bubble = true)
    {
        $this->subjectPrefix = $subjectPrefix;
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

        // Only email formatted log messages, not raw exceptions
        if (count($params['context']) && !isset($params['context']['SCRAPING'])) {
            return;
        }

        $params['exception'] = $params['message'];
        $subject = sprintf('%s: %s',
            $this->subjectPrefix,
            preg_replace('/(.*)\n[\s\S]*/', '$1', $params['exception'])
        );

        try {
            Mail::send('mail.exception', $params, function ($msg) use ($subject) {
                $msg->from([$this->from]);
                $msg->to([$this->to])->subject($subject);
            });
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}