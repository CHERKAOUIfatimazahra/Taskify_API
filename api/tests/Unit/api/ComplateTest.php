<?php

namespace Tests\Unit;

use App\Models\Task;
use PHPUnit\Framework\TestCase;

class ComplateTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_can_mark_task_as_completed()
    {
        $task = Task::factory()->create([
            'is_completed' => false,
        ]);

        $requestData = [
            'is_completed' => true,
        ];

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/complete", $requestData);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $task->id,
                'is_completed' => true,
            ]);

        $this->assertTrue(Task::find($task->id)->is_completed);
    }

    public function test_invalid_task_id_returns_404()
    {
        $requestData = [
            'is_completed' => true,
        ];

        $response = $this->patchJson("/api/v1/tasks/999/complete", $requestData);

        $response->assertStatus(404);
    }
}
