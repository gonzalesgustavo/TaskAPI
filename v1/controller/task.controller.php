<?php

//File Imports
require_once('../controller/Response.controller.php');
require_once('../model/Task.model.php');
require_once('query.controller.php');

class Task{
    // Get a single task
    public static function getTask($taskId, $readDB)
    {
        try
        {
            $taskArray = QueryBuilder::query(
                $readDB,
                array(
                    'id' => $taskId,
                    'qStr' => 'SELECT id, title,description,DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed FROM tbltasks WHERE id = :taskId;',
                    'errMsg' => 'taskId provided did not match any records in database'
                ),
                array(
                    ':taskId' => $taskId
                )
            );
            $returnData = array();
            $returnData['rows_returned'] = $taskArray['rowNum'];
            $returnData['tasks'] = $taskArray['tasks'];
            ResponseBuilder::successResponse(200, "Successfull Task found for id: " . $taskId, $returnData, true);
            exit;
        }
        catch(TaskException $tsk_ex)
        {
            ResponseBuilder:: errorResponse(500, $tsk_ex->getMessage(), $tsk_ex->getMessage());
            exit;
        }
        catch(PDOException $pdo_ex)
        {
            error_log("Completed Get request Error (task-controller) - " . $pdo_ex->getMessage(), 0);
            ResponseBuilder:: errorResponse(500, "Failed to get task", null);
            exit;
        }
    }
    // Get Tasks based on completion
    public static function getTaskByCompletedStatus($completedStatus, $readDB)
    {
        try
        {
            $taskArray = QueryBuilder::query(
                $readDB,
                array(
                    'qStr' => 'SELECT id, title,description,DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed FROM tbltasks WHERE completed = :completed'
                ),
                array(
                    ':completed' => $completedStatus
                )
            );
            // Key for request 
            $key = 'tasks'; 
            // Check if key is Y then task key = 'completed_tasks' else 'incomplete_tasks' for N
            if($completedStatus === 'Y')
            {
                $key = "completed_tasks";
            }
            else
            {
                $key = "incomplete_tasks";
            }
            $returnData = array();
            $returnData['rows_returned'] = $taskArray['rowNum'];
            $returnData['tasks'] = $taskArray['tasks'];
            ResponseBuilder::successResponse(200, "Successfull Tasks found for " . $key, $returnData, true);
            exit;
        }
        catch (TaskException $tsk_ex)
        {
            ResponseBuilder:: errorResponse(500, $tsk_ex->getMessage(), $tsk_ex->getMessage());
            exit;
        }
        catch (PDOException $pdo_ex)
        {
            error_log("Completed Get incomplete/complete request Error (task-controller) - " . $pdo_ex->getMessage(), 0);
            ResponseBuilder:: errorResponse(500, "Failed to get Task Completed Status", null);
            exit;
        }
    }
    // Get all tasks
    public static function getTasks($readDB)
    {
        try
        {
            $taskArray = QueryBuilder::query(
                $readDB,
                array(
                    'qStr' => 'SELECT id, title,description,DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed FROM tbltasks',
                    'errMsg' => 'no tasks to display'
                ),
                array()
            );
            $returnData = array();
            $returnData['rows_returned'] = $taskArray['rowNum'];
            $returnData['tasks'] = $taskArray['tasks'];
            ResponseBuilder::successResponse(200, "Tasks Found " . $taskArray['rowNum'], $returnData, true);
            exit;
        }
        catch (TaskException $tsk_ex)
        {
            ResponseBuilder:: errorResponse(500, $tsk_ex->getMessage(), $tsk_ex->getMessage());
            exit;
        }
        catch (PDOException $pdo_ex)
        {
            error_log("Completed Get All request Error (task-controller) - " . $pdo_ex->getMessage(), 0);
            ResponseBuilder:: errorResponse(500, "Failed to get all tasks", null);
            exit;
        }
    }
    // Get Tasks (Pagination)
    public static function getTasksPagintation($page_num, $readDB)
    {
        
        try
        {
            // Page Limit
            $pageLimit = 20;
            //get number of tasks in database
            $query = $readDB->prepare('SELECT count(id) as totalNumTsk FROM tbltasks');
            // Execute the query
            $query->execute();
            //Get Count of Id's
            $row = $query->fetch(PDO::FETCH_ASSOC);
            // Make sure it's numeric
            $taskCount = intval($row['totalNumTsk']);
            // work out how many pages necessary
            $numOfPages = ceil($taskCount/$pageLimit);
            // Handle if there are no tasks
            if($numOfPages == 0)
            {
                $numOfPages = 1;
            }
            // Handle unknown page or outside limit of pages available from DB
            if($page_num > $numOfPages)
            {
                ResponseBuilder::errorResponse(404, "Page not found", null);
                exit;
            }
            // Offset for row 
            $offset = ($page_num == 1 ? 0 : ($pageLimit * ($page_num - 1)));
        
            $taskArray = QueryBuilder::query(
                $readDB,
                array(
                    'qStr' => 'SELECT id, title,description,DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed FROM tbltasks LIMIT :pgLimit offset :offSet',
                    'errMsg' => 'paginaion error'
                ),
                array(
                    ':pgLimit' => $pageLimit,
                    ':offSet' => $offset
                )
            );
            $returnData = array();
            $returnData['rows_returned'] = $taskArray['rowNum'];
            $returnData['total_rows'] = $taskCount;
            $returnData['total_pages'] = $numOfPages;
            ($page_num < $numOfPages ? $returnData['has_next_page'] = true : $returnData['has_next_page'] = false);
            ($page_num > 1 ? $returnData['has_previous_page'] = true : $returnData['has_previous_page'] = false);
            $returnData['tasks'] = $taskArray['tasks'];
            ResponseBuilder::successResponse(200, "Successfull GET Request for all tasks for page " . $page_num, $returnData, true);
            exit;
        }
        catch (TaskException $tsk_ex)
        {
            ResponseBuilder:: errorResponse(500, $tsk_ex->getMessage(), $tsk_ex->getMessage());
            exit;
        }
        catch (PDOException $pdo_ex)
        {
            error_log("Completed Get All request Error (task-controller) - " . $pdo_ex->getMessage(), 0);
            ResponseBuilder:: errorResponse(500, "Failed to get all tasks", null);
            exit;
        }
    }
    // Delete Task
    public static function deleteTask($taskId, $writeDB)
    {
        try
        {
            $taskArray = QueryBuilder::query(
                $writeDB,
                array(
                    'qStr' => 'DELETE FROM tbltasks WHERE id = :taskId',
                    'errMsg' => 'taskId provided did not match any records in database, Failed to delete'
                ),
                array(
                    ':taskId' => $taskId
                )
            );
            ResponseBuilder::successResponse(200, "Task has been deleted with ID " . $taskId, null, true);
            exit;
        }
        catch(PDOException $pdo_ex)
        {
            ResponseBuilder::errorResponse(500, $pdo_ex->getMessage() . "Failed to Delete Task with ID " . $taskId, null);
            exit;
        }
    }
    // Add a new task
    public static function setTask($writeDB)
    {
        try
        {
            // Check if content type matches application/json
            if($_SERVER['CONTENT_TYPE'] !== 'application/json')
            {
                ResponseBuilder:: errorResponse(400, "Content Type is not set to application/json", null);
                exit;
            }
            // Get body 
            $rawPOSTData = file_get_contents('php://input');
            // If cannot decode send error
            if(!$jsonData = json_decode($rawPOSTData))
            {
                ResponseBuilder:: errorResponse(400, "Request body is Not Valid", null);
                exit;
            }
            //Make sure required fields are fullfilled
            if(!isset($jsonData->title) || !isset($jsonData->completed))
            {
                $messages = array(
                    (!isset($jsonData->title)) ? 'Title Field is required': '',
                    (!isset($jsonData->completed)) ? 'Completed Field is required': ''
                );
                ResponseBuilder:: errorResponse(400, $messages, null);
                exit;
            }
            // Build new TaskModel with data from Body
            $newTask = new TaskModel(null,$jsonData->title, (isset($jsonData->description) ? $jsonData->description : null), (isset($jsonData->deadline) ? $jsonData->deadline : null), $jsonData->completed);
            // Setup data to save to database
            $title = $newTask->getTitle();
            $description = $newTask->getDescription();
            $deadline = $newTask->getDeadline();
            $completed = $newTask->getCompletedStatus();

            $insertTaskArray = QueryBuilder::query(
                $writeDB,
                array(
                    'qStr' => 'INSERT INTO tbltasks (title, description, deadline, completed) VALUES (:title, :description, STR_TO_DATE(:deadline, \'%d/%m:Y %H:%i\'), :completed)',
                    'errMsg' => 'Failed to create task'
                ),
                array(
                    ':title' => $title,
                    ':deadline' => $deadline,
                    ':description' => $description,
                    ':completed' => $completed
                )
            );
            // Get previous task id
            $prev_task_id = $writeDB->lastInsertId();
            // Get data based on previous ID
            $taskArray = QueryBuilder::query(
                $writeDB,
                array(
                    'qStr' => 'SELECT id, title,description,DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed FROM tbltasks WHERE id = :prevInsertTskId',
                    'errMsg' => 'Failed to retrieve created task'
                ),
                array(
                    ':prevInsertTskId' => $prev_task_id
                )
            );
            $returnData = array();
            $returnData['rows_returned'] = $taskArray['rowNum'];
            $returnData['tasks'] = $taskArray['tasks'];
            ResponseBuilder::successResponse(200, "Successfull Task found added ", $returnData, true);
            exit;
        }
        catch(TaskException $tsk_ex)
        {
            ResponseBuilder:: errorResponse(500, $tsk_ex->getMessage(), $tsk_ex->getMessage());
            exit;
        }
        catch(PDOException $pdo_ex)
        {
            error_log("Completed Get request Error (task-controller) - " . $pdo_ex->getMessage(), 0);
            ResponseBuilder:: errorResponse(500, "Failed to get task", null);
            exit;
        }
    }
    // Update a task
    public static function updateTask($taskId, $writeDB)
    {
        try
        {
            // Check if content type matches application/json
            if($_SERVER['CONTENT_TYPE'] !== 'application/json')
            {
                ResponseBuilder:: errorResponse(400, "Content Type is not set to application/json", null);
                exit;
            }
            // Get body 
            $rawPOSTData = file_get_contents('php://input');
            // Check response
            if(!$jsonData = json_decode($rawPOSTData))
            {
               ResponseBuilder:: errorResponse(400, "Request body is Not Valid", null);
               exit;
            }
            // Find fields that need to be updated
            $update_fields = checkFields($jsonData);
            // Query to find data based on task id
            $task_from_old_array = QueryBuilder::query(
                $writeDB,
                array(
                    'qStr' => 'SELECT id, title,description,DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed FROM tbltasks WHERE id = :taskId',
                    'errMsg' => 'Failed to retrieve task with id - ' . $taskId
                ),
                array(
                    ':taskId' => $taskId
                )
            );
            $update_param_array = array(
                ':taskId' => $taskId
            );
            // Build params array based on what is to be updated
            if($update_fields['title'] === true)
            {
                $update_param_array[':title'] = $jsonData->title;
            }
            if($update_fields['description'] === true)
            {
                $update_param_array[':description'] = $jsonData->description;
            }
            if($update_fields['deadline'] === true)
            {
                $update_param_array[':deadline'] = $jsonData->deadline;
            }
            if($update_fields['completed'] === true)
            {
                $update_param_array[':completed'] = $jsonData->completed;
            }
            // Try to update
            $task_update_query = QueryBuilder::query(
                $writeDB,
                array(
                    'qStr' => "UPDATE tbltasks set {$update_fields['queryStr']} WHERE id = :taskId",
                    'errMsg' => 'Failed to update task with id - ' . $taskId
                ),
                $update_param_array
            );
            // Get updated task
            $updated_task_array = QueryBuilder::query(
                $writeDB,
                array(
                    'qStr' => 'SELECT id, title,description,DATE_FORMAT(deadline, "%d/%m/%Y %H:%i") as deadline, completed FROM tbltasks WHERE id = :taskId',
                    'errMsg' => 'Failed to retrieve updated task'
                ),
                array(
                    ':taskId' => $taskId
                )
            );
            $returnData = array();
            $returnData['rows_returned'] = $updated_task_array['rowNum'];
            $returnData['tasks'] = $updated_task_array['tasks'];
            ResponseBuilder::successResponse(200, "Successfull Task foun", $returnData, true);
            exit;
            
        }
        catch(TaskException $tsk_ex)
        {
            ResponseBuilder:: errorResponse(500, $tsk_ex->getMessage(), $tsk_ex->getMessage());
            exit;
        }
        catch(PDOException $pdo_ex)
        {
            error_log("Patch request Error (task-controller) - " . $pdo_ex->getMessage(), 0);
            print($pdo_ex->getMessage());
            ResponseBuilder:: errorResponse(500, "Failed to update task", null);
            exit;
        }
    }
}
// Check which fields are present in the body
function checkFields($body)
    {
        // Set true if fields present and need to be updated
        $title_updated = false;
        $description_updated = false;
        $deadline_updated = false;
        $completed_updated = false;
        // Init empty query string
        $return_query = "";
        // See what fields were passed in and add to query string
        foreach ($body as $key => $value) {
            switch($key)
            {
                case 'title':
                    $title_updated = true;
                    $return_query .= "title= :title, ";
                    break;
                case 'description':
                    $description_updated = true;
                    $return_query .= "description = :description, ";
                    break;
                case 'deadline':
                    $deadline_updated = true;
                    $return_query .= "STR_TO_DATE(:deadline, \'%d/%m:Y %H:%i\'), ";
                    break;
                case 'completed':
                    $completed_updated = true;
                    $return_query .= "completed = :completed, ";
                    break;
            }
        }
        // make sure at least one field is set to update
        if($title_updated === false && $description_updated === false && $deadline_updated === false && $completed_updated === false)
        {
            ResponseBuilder:: errorResponse(400, "No task fields provided", null);
            exit;
        }
        //remove last comma of query string
        $remove_comma = rtrim($return_query, ", ");
        $return_query_final = str_replace(' ', '', $remove_comma);
        return array(
            "title" => $title_updated,
            "description" => $description_updated,
            "deadline" => $deadline_updated,
            "completed" => $completed_updated,
            "queryStr" => $return_query_final
        );
        
    }