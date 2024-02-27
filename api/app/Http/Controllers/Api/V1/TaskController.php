<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @SWG\Get(
     *     path="/v1/tasks",
     *     tags={"Tasks"},
     *     summary="List all tasks",
     *     description="Get a list of all tasks.",
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Task")
     *         )
     *     ),
     *     security={{"api_key": {}}}
     * )
     */
    public function index()
    {
        $task = Task::all();
        return TaskResource::collection($task);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @SWG\Post(
     *     path="/v1/tasks",
     *     tags={"Tasks"},
     *     summary="Create a new task",
     *     description="Create a new task.",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/Task"),
     *         description="Task object"
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Successful operation",
     *         @SWG\Schema(ref="#/definitions/Task")
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     security={{"api_key": {}}}
     * )
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(),[
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'message' => 'Validation Error']);
        }
        $userId = Auth::id();
        
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => $userId
        ]);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     *
     * @SWG\Get(
     *     path="/v1/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Display a specific task",
     *     description="Display the details of a specific task.",
     *     @SWG\Parameter(
     *         name="task",
     *         in="path",
     *         type="integer",
     *         required=true,
     *         description="ID of the task to retrieve"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(ref="#/definitions/Task")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     security={{"api_key": {}}}
     * )
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @SWG\Put(
     *     path="/v1/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Update a task",
     *     description="Update the details of a specific task.",
     *     @SWG\Parameter(
     *         name="task",
     *         in="path",
     *         type="integer",
     *         required=true,
     *         description="ID of the task to update"
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/Task"),
     *         description="Updated task object"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation",
     *         @SWG\Schema(ref="#/definitions/Task")
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     security={{"api_key": {}}}
     * )
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validator = validator($request->all(),[
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'message' => 'Validation Error']);
        }

        $task->update($request->all());
        return new TaskResource($task);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @SWG\Delete(
     *     path="/v1/tasks/{task}",
     *     tags={"Tasks"},
     *     summary="Delete a task",
     *     description="Delete a specific task.",
     *     @SWG\Parameter(
     *         name="task",
     *         in="path",
     *         type="integer",
     *         required=true,
     *         description="ID of the task to delete"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Task not found"
     *     ),
     *     security={{"api_key": {}}}
     * )
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
