<?php

require_once('../model/Response.model.php');

$response = new Response();
$response->setSuccess(true);
$response->setHttpStatusCode(200);
$response->addMessage("test message 1");
$response->addMessage("test message 2");
$response->send();
