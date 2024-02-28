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
     * @OA\Patch(
     *     path="/api/v1/tasks/{task}/complete",
     *     tags={"Tasks"},
     *     summary="Mark a task as completed",
     *     description="Mark a task as completed by updating the 'is_completed' field.",
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         description="ID of the task to mark as completed",
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
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="is_completed", type="boolean", example=true),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task marked as completed successfully",
     *     ),
     *     @OA\Response(
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
