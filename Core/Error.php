<?php

namespace Core;

class Error
{
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0)
        {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    public static function exceptionHandler($exception)
    {
        $code = $exception->getCode();
        if ($code != 404)
        {
            $code = 500;
        }
        http_response_code($code);

        $message = "";
        if (\App\Config::SHOW_ERRORS)
        {
            $message .= "<h1>Fatal Error</h1>
            <p>Uncaught exception: ". get_class($exception) ." </p>
            <p>Message: ". $exception->getMessage() ."</p>
            <p>Stack trace: <pre>". $exception->getTraceAsString() ."</pre></p>
            <p>Thrown in ". $exception->getFile() ." on line ". $exception->getLine() . "</p>";
        }
        else
        {
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);

            $message_error = "Uncaught exception: ". get_class($exception) ." with message "
                . $exception->getMessage() . "\n Stack Trace: " . $exception->getTraceAsString(). "
                \n Thrown in " . $exception->getFile();

            error_log($message_error);

            View::renderTemplate("{$code}.html");
        }

        echo $message;
    }
}
