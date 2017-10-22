<?php namespace App\Helpers;

use Log;
class LogFormatter
{

    public static function error($exceptions)
    {
        $errorLog = "{$exceptions['message']}\n";
        unset($exceptions['message']);
        foreach ($exceptions as $key => $exception) {
            $message = ucfirst($key) . ":\n";
            $message .= json_encode($exception);
            $errorLog .= $message . "\n";
        }

        Log::error($errorLog);
    }

    public static function info($data)
    {
        Log::info(json_encode($data));
    }
}
