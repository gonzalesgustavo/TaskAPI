<?php
require_once('Response.controller.php');
require_once('../model/Task.model.php');

class QueryBuilder {
    public static function query($db_connection, $setup, $params)
    {
        // Get data based on previous ID
        $query = $db_connection->prepare($setup['qStr']);
        // Execute Query
        $query->execute(isset($params) ? $params : null);
        // Get Row Count
        $rowCount = $query->rowCount();
        // If rowCount size = 0 error out
        if($rowCount === 0)
        {
            ResponseBuilder:: errorResponse(500, isset($setup['errMsg']) ? $setup['errMsg'] : 'Failed to retrieve data', null);
            exit;
        }
        // Array for data retrieved
        $taskArray = array();
        // Loop through Data
        while($row = $query->fetch(PDO::FETCH_ASSOC))
        {
            $task = new TaskModel(
                $row['id'], 
                $row['title'], 
                $row['description'], 
                $row['deadline'], 
                $row['completed']
            );
            $taskArray[] = $task->printHelper();
        }
        return array(
            'tasks' => $taskArray,
            'rowNum' => $rowCount
        );
    }
}