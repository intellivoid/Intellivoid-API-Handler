<?php


    use Handler\Handler;

    require(__DIR__ . DIRECTORY_SEPARATOR .'resources' . DIRECTORY_SEPARATOR . 'Handler' . DIRECTORY_SEPARATOR . 'Handler.php');

    Handler::handle();


    $match = Handler::$Router->match();
    if( is_array($match) && is_callable( $match['target'] ) )
    {
        call_user_func_array( $match['target'], $match['params'] );
    }
    else
        {
        // no route was matched
        header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    }