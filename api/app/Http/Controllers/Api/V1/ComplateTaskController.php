<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class ComplateTaskController extends Controller
{
    /**
     * Mark a task as completed.
     *
     * @SWG\Patch(
     *     path="/api/v1/tasks/{task}/complete",
     *     tags={"Tasks"},
     *     summary="Mark a task as completed",
     *     description="Mark a task as completed by updating the 'is_completed' field.",
     *     @SWG\Parameter(
     *         name="task",
     *         in="path",
     *         type="integer",
     *         required=true,
     *         description="ID of the task to mark as completed"
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="is_completed", type="boolean", example=true),
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Task marked as completed successfully",
     *         @SWG\Schema(ref="#/definitions/Task")
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Task not found"
     *     )
     * )
     */
    public function __invoke(Request $request, Task $task)
    {
        $task->is_completed = $request->is_completed;
        $task->save();

        return TaskResource::make($task);
}
}
