<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function __construct(){
        
        $this->middleware('auth:sanctum');
    }
    public function index()
    {
        $task = Task::all();
        $this->authorize('view', $task);
        return TaskResource::collection($task);
    }

    /**
     * Show the form for creating a new resource.
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

        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
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
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
