<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    private User $user;
    private Task $task;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        TaskStatus::factory()->create();
        $this->task = Task::factory()->create();
    }

    public function testTasksPage(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('tasks.index'));

        $response->assertOk();
    }

    public function testStoreTask(): void
    {
        $data = Task::factory()->make()->only([
            'name',
            'description',
            'status_id',
            'assigned_to_id',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('tasks.store'), $data);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testNotCreateStoreTaskWithoutAuthorized(): void
    {
        $data = Task::factory()->make()->only([
            'name',
            'description',
            'status_id',
            'assigned_to_id',
        ]);

        $response = $this->post(route('tasks.store'), $data);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $data);
    }

    public function testEditPage(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('tasks.edit', $this->task));

        $response->assertOk();
    }

    public function testUpdateTask(): void
    {
        $this->task = Task::factory()->create([
            'created_by_id' => $this->user->id,
        ]);
        $data = Task::factory()->make()->only([
            'name',
            'description',
            'status_id',
            'assigned_to_id',
        ]);
        $response = $this->actingAs($this->user)
            ->put(route('tasks.update', $this->task), $data);
        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testNotUpdateTaskWithoutAuthorized(): void
    {
        $data = Task::factory()->make()->only([
            'name',
            'description',
            'status_id',
            'assigned_to_id',
        ]);

        $response = $this->put(route('tasks.update', $this->task), $data);
        $response->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $data);
    }

    public function testDeleteTask(): void
    {
        $taskToDelete = Task::factory()->create(['created_by_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->delete(route('tasks.destroy', $taskToDelete));

        $response->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $taskToDelete->id]);
    }

    public function testNotDeleteTaskWithoutCreater(): void
    {
        $taskData = Task::factory()->make()->only([
            'created_by_id',
            'name',
            'description',
            'status_id',
            'assigned_to_id',
        ]);

        $this->actingAs($this->user)
            ->post(route('tasks.store'), $taskData);

        $createdTask = Task::where('name', $taskData['name'])->first();

        $user2 = User::factory()->create();
        $responseUser2 = $this->actingAs($user2)
            ->delete(route('tasks.destroy', $createdTask));
        $responseUser2->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $createdTask->id]);
    }

    public function testNotCreateTaskUnauthorized(): void
    {
        $response = $this->get(route('tasks.create'));
        $response->assertStatus(403);
    }

    public function testNotEditTaskUnauthorized(): void
    {
        $response = $this->get(route('tasks.edit', $this->task));
        $response->assertStatus(403);
    }
}
