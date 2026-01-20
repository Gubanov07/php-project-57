<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskStatus;

class TaskTest extends TestCase
{
    public function testIndex(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertOk();
    }

    public function testCreateForGuest(): void
    {
        $response = $this->get(route('tasks.create'));
        $response->assertRedirect('/login');
    }

    public function testCreateForUser(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('tasks.create'));

        $response->assertOk();
        $response->assertSee(__('task.create_task'));
    }

    public function testStoreForGuest(): void
    {
        $status = TaskStatus::factory()->create();

        $response = $this->post(route('tasks.store'), [
            'name' => 'Test Task',
            'status_id' => $status->id,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('tasks', ['name' => 'Test Task']);
    }

    public function testStoreForUser(): void
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();
        $assignee = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('tasks.store'), [
                'name' => 'Test Task',
                'description' => 'Test description',
                'status_id' => $status->id,
                'assigned_to_id' => $assignee->id,
            ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'name' => 'Test Task',
            'created_by_id' => $user->id,
        ]);
    }

    public function testShow(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.show', $task));
        $response->assertOk();
        $response->assertSee($task->name);
    }

    public function testEditForGuest(): void
    {
        $task = Task::factory()->create();

        $response = $this->get(route('tasks.edit', $task));
        $response->assertRedirect('/login');
    }

    public function testEditForCreator(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get(route('tasks.edit', $task));

        $response->assertOk();
        $response->assertSee($task->name);
    }

    public function testUpdateForGuest(): void
    {
        $task = Task::factory()->create();
        $status = TaskStatus::factory()->create();

        $response = $this->put(route('tasks.update', $task), [
            'name' => 'Updated Task',
            'status_id' => $status->id,
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('tasks', ['name' => 'Updated Task']);
    }

    public function testDestroyForGuest(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task));
        $response->assertRedirect('/login');
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function testDestroyByNonCreator(): void
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $creator->id]);

        $response = $this->actingAs($user)
            ->delete(route('tasks.destroy', $task));

        $response->assertForbidden();
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function testDestroyByCreator(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['created_by_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
