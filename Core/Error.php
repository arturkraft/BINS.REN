<?php

namespace Core;


/**
 * Error and Exception handler
 *
 * PHP version 8.3
 */
class Error
{

    /**
     * Error handler. Convert all errors to exceptions
     * by throwing an ErrorException
     * 
     * @param int $level  Error level
     * @param string $message  Error message
     * @param string $file  Filename the error was raised in
     * @param int $line  Line number in the file
     * 
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) //to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Exception handler.
     * 
     * @param Exception $exception  The exception
     * 
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        //Code is 404 or 500
        $code = $exception->getCode();
        $code = $code == 404 ? 404 : 500;
        http_response_code($code);

        $new_line = \App\Config::SHOW_ERRORS ? '</p><p>' : "\n";
        $pre_tag_open = \App\Config::SHOW_ERRORS ? '<pre>' : '';
        $pre_tag_close = \App\Config::SHOW_ERRORS ? '</pre>' : '';
        $message = \App\Config::SHOW_ERRORS ? '<h1>Fatal error</h1><p>' : '';
        $message .= $new_line . 'Uncaught exception: ' . get_class($exception);
        $message .= $new_line . 'Message: ' . $exception->getMessage();
        $message .= $new_line . 'Stack trace: ' . $pre_tag_open . $exception->getTraceAsString() . $pre_tag_close;
        $message .= $new_line . 'Thrown in: ' . $exception->getFile() . ' on line: ' . $exception->getLine() . $new_line;
        if (\App\Config::SHOW_ERRORS) {
            echo $message . '</p>';
        } else {
            ini_set('error_log', dirname(__DIR__) . '/logs/' . date("Y-m-d") . '.txt');
            error_log($message);

            View::renderTemplate("$code.html");
        }
    }
}
