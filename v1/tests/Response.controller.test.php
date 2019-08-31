<?php

require_once('../controller/Response.controller.php');

$messages = array(
    "name of is type",
    "now is not never"
);

ResponseBuilder::errorResponse(400,$messages, null);