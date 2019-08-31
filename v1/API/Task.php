<?php
// file imports
require_once('../controller/db.controller.php');
require_once('../controller/Response.controller.php');
require_once('../controller/task.controller.php');

try
{
    // Connect to databases
    $writeDB = Database::connectWriteDB();
    $readDB = Database::connectReadDB();
}
catch (PDOException $ex)
{
    error_log("connection Error (task.php) - ". $ex, 0);
    ResponseBuilder::errorResponse(500, "Database Connection Error", null);
    exit;
}

if(array_key_exists("taskId", $_GET))
{
    $taskId = $_GET['taskId'];
    if($taskId == '' || !is_numeric($taskId))
    {
        error_log("TaskID Error (task.php) - ", 0);
        ResponseBuilder::errorResponse(400, "taskID cannot be blank and must be Numeric", null);
        exit;
    }
    switch($_SERVER['REQUEST_METHOD'])
    {
        case 'GET':
            // Get a single task
            Task::getTask($taskId, $readDB);
            break;
        case 'PATCH':
            Task::updateTask($taskId, $writeDB);
            break;
        case 'PUT':
            Task::updateTask($taskId, $writeDB);
            break;
        case 'DELETE':
            //Delete a single task
            Task::deleteTask($taskId, $writeDB);
            break;
        default:
            ResponseBuilder::errorResponse(405, "Request Method Not Allowed", "Bad Request");
            exit;
    }
}
else if(array_key_exists("completed", $_GET))
{
    $completedStatus = strtoupper($_GET['completed']);
    switch($_SERVER['REQUEST_METHOD'])
    {
        case 'GET':
            // Get a single task
            Task::getTaskByCompletedStatus($completedStatus, $readDB);
            break;
        default:
            ResponseBuilder::errorResponse(405, "Request Method Not Allowed", "Bad Request");
            exit;
    }
}
else if(array_key_exists("page", $_GET))
{
    $pageNum = $_GET['page'];
    if($pageNum == '' || !is_numeric($pageNum))
    {
        error_log("Page Error (task.php) - page number or value given did not meet specifications ", 0);
        ResponseBuilder::errorResponse(400, "page number cannot be blank and must be Numeric", null);
        exit;                      
    }
    switch($_SERVER['REQUEST_METHOD'])
    {
        case 'GET':
             // Get aall tasks pagination
            Task::getTasksPagintation($pageNum, $readDB);
            break;
        default:
            ResponseBuilder::errorResponse(405, "Request Method Not Allowed", "Bad Request");
            exit;
    }
}
else if(empty($_GET))
{
    switch($_SERVER['REQUEST_METHOD'])
    {
        case 'GET':
            // Get all tasks
            Task::getTasks($readDB);
            break;
        case 'POST':
            //Save task to Database
            Task::setTask($writeDB);
            break;
        default:
            ResponseBuilder::errorResponse(405, "Request Method Not Allowed", "Bad Request");
            exit;
    }
}