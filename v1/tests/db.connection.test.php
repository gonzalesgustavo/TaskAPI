<?php
require_once('../controller/db.controller.php');
require_once('../controller/Response.controller.php');
try{
    $writeDB = Database::connectWriteDB();
    $readDB = Database::connectReadDB();
    ResponseBuilder::successResponse(200, "Database Connection Successful", null, false);
} 
catch(PDOException $e)
{
    ResponseBuilder::errorResponse(500, "Database Connection Error", $e);
    exit;
}