<?php

class TaskException extends Exception{ }

    class TaskModel {

        private $_id;
        private $_title;
        private $_description;
        private $_deadline;
        private $_completed;

        //Class Constructor
        public function __construct(
            $id,
            $title,
            $description,
            $deadline,
            $completedStatus
        )
        {
            $this->setId($id);
            $this->setTitle($title);
            $this->setDescription($description);
            $this->setDeadline($deadline);
            $this->setCompletedStatus($completedStatus);
        }
        //getters
        public function getId()
        {
            return $this->_id;
        }

        public function getTitle()
        {
            return $this->_title;
        }

        public function getDescription()
        {
            return $this->_description;
        }

        public function getDeadline()
        {
            return $this->_deadline;
        }

        public function getCompletedStatus()
        {
            return $this->_completed;
        }

        //setters
        public function setId($id)
        {
            $this->_id = $id;
        }

        public function setTitle($title)
        {   
            // Handle database restrictions
            if(strlen($title) < 0 || strlen($title) > 255)
            {
                // Throw Exception
                throw new TaskException("Task Title Error");
            }
            $this->_title = $title;
        }

        public function setDescription($description)
        {
            // Handle database restrictions
            if(($description !== null) && (strlen($description) > 16777215))
            {
                //Throw Exception
                throw new TaskException("Task Description Error");
            }
            $this->_description = $description;
        }

        public function setDeadline($deadline)
        {
            $date = date_create_from_format('d/m/Y H:i', $deadline);
            if(($deadline !== null) && (date_format($date,'d/m/Y H:i') !== $deadline))
            {
                throw new TaskException("Task deadline date time Error");
            }
            $this->_deadline = $deadline;
        }

        public function setCompletedStatus($completedStatus)
        {
            if(strtoupper($completedStatus) !== 'Y' && strtoupper($completedStatus) !== 'N')
            {
                throw new TaskException("Task Completed Must BE Y || N");
            }
             $this->_completed = $completedStatus;
        }

        public function printHelper()
        {
            $task_array = array(
                'description' => $this->getDescription(),
                'deadline'    => $this->getDeadline(),
                'completed'   => $this->getCompletedStatus(),
                'title'       => $this->getTitle(),
                'id'          => $this->getId()
            );
            return $task_array;
        }
    }
