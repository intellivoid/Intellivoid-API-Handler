<?php

    use Handler\GenericResponses\ResourceNotFound;
    use Handler\Handler;

    require(__DIR__ . DIRECTORY_SEPARATOR .'resources' . DIRECTORY_SEPARATOR . 'Handler' . DIRECTORY_SEPARATOR . 'Handler.php');

    Handler::handle();
    $match = Handler::$Router->match();

    if(is_array($match) && is_callable($match['target']))
    {
        call_user_func_array($match['target'], $match['params']);
    }
    else
    {
        ResourceNotFound::executeResponse();
        exit();
    }