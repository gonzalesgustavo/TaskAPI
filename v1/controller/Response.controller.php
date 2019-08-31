<?php

require_once('../model/Response.model.php');

class ResponseBuilder {
    public static function errorResponse($errorCode, $message, $data)
    {
        $response = new Response();
        $response->setHttpStatusCode($errorCode);
        $response->setSuccess(false);
        if(gettype($message) == 'string')
        {
            $response->addMessage($message);
        }
        else if(gettype($message) == 'array')
        {
            foreach ($message as $value)
            {
                $response->addMessage($value);
            }
        }
       
        $response->setData($data);
        $response->send();
    }

    public static function successResponse($code,$message, $data, $cache)
    {
        $response = new Response();
        $response->setHttpStatusCode($code);
        $response->setSuccess(true);
        $response->addMessage($message);
        if($cache !== true)
        {
            $response->toCache(true);
        }
        $response->setData($data);
        $response->send();
    }
}