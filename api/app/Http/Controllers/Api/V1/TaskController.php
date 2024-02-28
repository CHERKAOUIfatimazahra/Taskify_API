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
 * @OA\Get(
 *     path="/v1/tasks",
 *     tags={"Tasks"},
 *     summary="List all tasks",
 *     description="Get a list of all tasks.",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Task title"),
 *                 @OA\Property(property="description", type="string", example="Task description"),
 *                 @OA\Property(property="is_completed", type="boolean", example=true),
 *             )
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
     * @OA\Post(
     *     path="/v1/tasks",
     *     tags={"Tasks"},
     *     summary="Create a new task",
     *     description="Create a new task.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *     ),
     *     @OA\Response(
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
 * @OA\Get(
 *     path="/api/v1/tasks/{task}",
 *     tags={"Tasks"},
 *     summary="Display a specific task",
 *     description="Display the details of a specific task.",
 *     @OA\Parameter(
 *         name="task",
 *         in="path",
 *         description="ID of the task to retrieve",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *     ),
 *     @OA\Response(
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
 * @OA\Put(
 *     path="/api/v1/tasks/{task}",
 *     tags={"Tasks"},
 *     summary="Update a task",
 *     description="Update the details of a specific task.",
 *     @OA\Parameter(
 *         name="task",
 *         in="path",
 *         description="ID of the task to update",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @OA\Response(
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
 * @OA\Delete(
 *     path="/api/v1/tasks/{task}",
 *     tags={"Tasks"},
 *     summary="Delete a specific task",
 *     description="Delete a specific task from the system.",
 *     @OA\Parameter(
 *         name="task",
 *         in="path",
 *         description="ID of the task to delete",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Task deleted successfully"
 *     ),
 *     @OA\Response(
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
