<?php

class Response {

    // Response variables
    private $_success;
    private $_httpStatusCode;
    private $_messages = array();
    private $_data;
    // Cache initial response to reduce calls to the server
    private $_toCache = false;
    private $_responseData = array();

    //setters
    public function setSuccess($succes)
    {
        $this->_success = $succes;
    }

    public function setHttpStatusCode($statusCode)
    {
        $this->_httpStatusCode = $statusCode;
    }

    public function addMessage($message)
    {
        $this->_messages[] = $message;
    }

    public function setData($data)
    {
        $this->_data = $data;
    }

    public function toCache($toCache)
    {
        $this->_toCache = $toCache;
    }

    public function send()
    {
        // Set content type 
        header('Content-type: application/json;charset=utf8');

        //chech if toChache is true
        if($this->_toCache == true)
        {
            // Allow cache
            header('Cache-control: max-age=60');
        } 
        else 
        {
            // refuse cahce
            header('Cache-control: no-cache, no-store');
        }

        if(($this->_success !== false && $this->_success !== true) || (!is_numeric($this->_httpStatusCode)))
        {
            //Set response code to 500 (Server Errror)
            http_response_code(500);
            //Build Response
            $this->_responseData['statusCode'] = 500;
            $this->_responseData['success'] = false;
            $this->addMessage("Response Creation Error");
            $this->_responseData['Messages'] = $this->_messages;
        } 
        else
        {
            // Successful Response
            http_response_code($this->_httpStatusCode);
            //Build Response
            $this->_responseData['statusCode'] = $this->_httpStatusCode;
            $this->_responseData['success'] = $this->_success;
            $this->_responseData['messages'] = $this->_messages;
            $this->_responseData['data'] = $this->_data;
        }
        echo json_encode($this->_responseData);
    }
}