# Task API (PHP)
---

### Description:
Bakend RestAPI for tasks. Uses JWT to gain access to protected routes. Uses MySQL and PDO to interact with the database.

---
## Sample Task:

Types:
```typescript
  description:string,
  deadline: Date,
  completed: string,
  title:string,
  id:number
```
sample task:
```json
{
  "description": "Complete the stack wash before closing",
  "deadline": "28/08/2019 06:00",
  "completed": "N",
  "title": "Wash Dishes",
  "id": 2
}
```


## Routes
 **Get a single task**: taskAPI/v1/tasks/taskId(numeric)  |  METHOD: GET

 Example Response:
 ```json
 {
    "statusCode": 200,
    "success": true,
    "messages": [
        "Successfull Task found for id: 2"
    ],
    "data": {
        "rows_returned": 1,
        "tasks": [
            {
                "description": "Complete the stack wash before closing",
                "deadline": "28/08/2019 06:00",
                "completed": "N",
                "title": "Wash Dishes",
                "id": 2
            }
        ]
    }
}
 ```

 **Get all completed tasks**: taskAPI/v1/tasks/complete | METHOD: GET

  Example Response:
 ```json
{
  "statusCode": 200,
  "success": true,
  "messages": [
    "Successfull Tasks found for completed_tasks"
  ],
  "data": {
    "rows_returned": 1,
    "completed_tasks": [
      {
        "description": "Remember the place only accepts COINS!",
        "deadline": "27\/08\/2019 06:30",
        "completed": "Y",
        "title": "Wash Floor Mats",
        "id": 3
      }
    ]
  }
}
 ```

 **Get all incomplete tasks**: taskAPI/v1/tasks/incomplete | METHOD: GET

  Example Response:
 ```json
 {
  "statusCode": 200,
  "success": true,
  "messages": [
    "Successfull Tasks found for incomplete_tasks"
  ],
  "data": {
    "rows_returned": 2,
    "incomplete_tasks": [
      {
        "description": "Make sure to add wash soap this time",
        "deadline": "30\/08\/2019 02:06",
        "completed": "N",
        "title": "DO Laundry",
        "id": 1
      },
      {
        "description": "Complete the stack wash before closing",
        "deadline": "28\/08\/2019 06:00",
        "completed": "N",
        "title": "Wash Dishes",
        "id": 2
      }
    ]
  }
}
 ```
**Add a new task**: taskAPI/v1/tasks | METHOD: POST

Sample Body JSON:
```json
{
	"title": "Walk Dog",
	"description": "Use flea and tick Soap Breed Friendly",
	"deadline": "01/10/2019 17:00",
	"completed": "N"
}
```

Sample Response:
```json
{
  "statusCode": 200,
  "success": true,
  "messages": [
    "Successfull Task found added "
  ],
  "data": {
    "rows_returned": 1,
    "tasks": [
      {
        "description": "Use flea and tick Soap Breed Friendly",
        "deadline": null,
        "completed": "N",
        "title": "Walk Dog",
        "id": 6
      }
    ]
  }
}
```

**Update a task**: taskAPI/v1/tasks/taskId(numeric) | METHOD: PATCH | PUT

Sample Body:
```json
{
	"title": "Wash the Floor Mats"
}
```

Sample Response:
```json
{
  "statusCode": 200,
  "success": true,
  "messages": [
    "Successfull Task foun"
  ],
  "data": {
    "rows_returned": 1,
    "tasks": [
      {
        "description": "Remember the place only accepts COINS!",
        "deadline": "27\/08\/2019 06:30",
        "completed": "Y",
        "title": "Wash the Floor Mats",
        "id": 3
      }
    ]
  }
}
```

  **Delete a single task**: taskAPI/v1/tasks/taskId(numeric) | METHOD: DELETE

  Example Response:
 ```json
 {
  "statusCode": 200,
  "success": true,
  "messages": [
    "Task has been deleted with ID 4"
  ],
  "data": null
 ```

 ---
 ---

 ## Logs

  [ X ] GET
  [ X ] POST
  [ X ] PATCH
  [ X ] PUT
  [ X ] DELETE

  [   ] JWT