<?php

namespace Tests\Unit\Api\v1;

use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a new task in API version 2.
     *
     * @return void
     */
    // public function test_can_list_tasks()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $response = $this->getJson('/api/v1/tasks');

    //     $response->assertStatus(200);
    // }

    public function test_can_create_task()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'is_complated' => 'false'
        ];

        $response = $this->postJson('/api/v1/tasks', $taskData);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('tasks',[
            'title'=>'task title',
            'description'=>'task description',
            'is_completed'=> false,
        ]);
        // $response->assertStatus(201)
        //     ->assertJsonStructure([
        //         'data' => [
        //             'id',
        //             'title',
        //             'description',
        //             'is_completed',
        //         ]
        //     ]);
    }

    // public function test_can_show_task()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user);

    //     $task = Task::factory()->create();

    //     $response = $this->getJson('/api/v1/tasks/' . $task->id);

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'data' => [
    //                 'id' => $task->id,
    //                 'title' => $task->title,
    //                 'description' => $task->description,
    //                 'is_completed' => $task->is_completed
    //             ]
    //         ]);
    // }

    public function test_can_update_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        // $updatedData = [
        //     'title' => 'Updated Task',
        //     'description' => 'Updated Description'
        // ];
        // $response = $this->putJson('/api/v1/tasks/' . $task->id, $updatedData);

        $response = $this->putJson('/api/v1/tasks/'.$task->id,[
            'title' => 'update title',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks',[
            'id'=> $task->id,
            'title'=>'task title',
            'description'=>'task description',
            'is_completed'=> false,
        ]);
        // $response->assertStatus(200)
        //     ->assertJson([
        //         'data' => [
        //             'id' => $task->id,
        //             'title' => $updatedData['title'],
        //             'description' => $updatedData['description']
        //         ]
        //     ]);
    }

    public function test_can_delete_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->deleteJson('/api/v1/tasks/'.$task->id);

        $response->assertStatus(200);
        $this->assertDeleted($task); 
    }
}