<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testIndex(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertOk();
    }

    public function testCreateForGuest(): void
    {
        $response = $this->get(route('task_statuses.create'));
        $response->assertRedirect('/login');
    }

    public function testCreateForUser(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get(route('task_statuses.create'));
        
        $response->assertOk();
    }

    public function testStoreForGuest(): void
    {
        $response = $this->post(route('task_statuses.store'), [
            'name' => 'Test Status'
        ]);
        
        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('task_statuses', ['name' => 'Test Status']);
    }

    public function testStoreForUser(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post(route('task_statuses.store'), [
                'name' => 'Test Status'
            ]);
        
        $response->assertRedirect(route('task_statuses.index'));
        $this->assertDatabaseHas('task_statuses', ['name' => 'Test Status']);
    }

    public function testEditForGuest(): void
    {
        $status = TaskStatus::factory()->create();
        
        $response = $this->get(route('task_statuses.edit', $status));
        $response->assertRedirect('/login');
    }

    public function testUpdateForGuest(): void
    {
        $status = TaskStatus::factory()->create();
        
        $response = $this->put(route('task_statuses.update', $status), [
            'name' => 'Updated Status'
        ]);
        
        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('task_statuses', ['name' => 'Updated Status']);
    }

    public function testDestroyForGuest(): void
    {
        $status = TaskStatus::factory()->create();
        
        $response = $this->delete(route('task_statuses.destroy', $status));
        $response->assertRedirect('/login');
        $this->assertDatabaseHas('task_statuses', ['id' => $status->id]);
    }

    public function testDestroyWithTasks(): void
    {
        $user = User::factory()->create();
        $status = TaskStatus::factory()->create();
        
        $response = $this->actingAs($user)
            ->delete(route('task_statuses.destroy', $status));

        $this->markTestSkipped('Requires Task model implementation');
    }
}
