<?php

namespace Tests\Unit\Api\v1;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Response;

class ComplateTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_can_mark_task_as_completed()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $task = Task::factory()->create([
            'user_id'=>$user->id,  
            'is_completed' => false,
        ]);

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/complete", [
            'is_completed' => true,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks',[
                'id' => $task->id,
                'is_completed' => true,
            ]);

    }
}
