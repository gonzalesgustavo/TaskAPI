<?php

require_once('../model/Task.model.php');

try
{
    $task = new TaskModel(1, "Title One", "Description test", "01/01/2019 12:00", "N");
    header('Content-typeL application/json;charset=UTF-8');
    echo json_encode($task->printHelper());
}
catch(TaskEcxeption $ex)
{
    echo "Error: " .$ex->getMessage();
}