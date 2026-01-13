<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Label;
use App\Models\User;
use App\Models\Task;

class LabelTest extends TestCase
{
    public function testIndex(): void
    {
        $response = $this->get(route('labels.index'));
        $response->assertOk();
    }

    public function testCreateForGuest(): void
    {
        $response = $this->get(route('labels.create'));
        $response->assertRedirect('/login');
    }

    public function testCreateForUser(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get(route('labels.create'));
        
        $response->assertOk();
        $response->assertSee(__('label.create_label'));
    }

    public function testStoreForGuest(): void
    {
        $response = $this->post(route('labels.store'), [
            'name' => 'Test Label',
        ]);
        
        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('labels', ['name' => 'Test Label']);
    }

    public function testStoreForUser(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post(route('labels.store'), [
                'name' => 'Test Label',
                'description' => 'Test description',
            ]);
        
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['name' => 'Test Label']);
    }

    public function testEditForGuest(): void
    {
        $label = Label::factory()->create();
        
        $response = $this->get(route('labels.edit', $label));
        $response->assertRedirect('/login');
    }

    public function testUpdateForGuest(): void
    {
        $label = Label::factory()->create();
        
        $response = $this->put(route('labels.update', $label), [
            'name' => 'Updated Label',
        ]);
        
        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('labels', ['name' => 'Updated Label']);
    }

    public function testDestroyForGuest(): void
    {
        $label = Label::factory()->create();
        
        $response = $this->delete(route('labels.destroy', $label));
        $response->assertRedirect('/login');
        $this->assertDatabaseHas('labels', ['id' => $label->id]);
    }

    public function testDestroyLabelWithTask(): void
    {
        $user = User::factory()->create();
        $label = Label::factory()->create();
        $task = Task::factory()->create();
        
        $task->labels()->attach($label->id);
        
        $response = $this->actingAs($user)
            ->delete(route('labels.destroy', $label));
        
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseHas('labels', ['id' => $label->id]);
    }

    public function testDestroyLabelWithoutTask(): void
    {
        $user = User::factory()->create();
        $label = Label::factory()->create();
        
        $response = $this->actingAs($user)
            ->delete(route('labels.destroy', $label));
        
        $response->assertRedirect(route('labels.index'));
        $this->assertDatabaseMissing('labels', ['id' => $label->id]);
    }

    public function testTaskWithLabels(): void
    {
        $user = User::factory()->create();
        $labels = Label::factory()->count(3)->create();
        $labelIds = $labels->pluck('id')->toArray();
        
        $response = $this->actingAs($user)
            ->post(route('tasks.store'), [
                'name' => 'Task with Labels',
                'status_id' => \App\Models\TaskStatus::factory()->create()->id,
                'labels' => $labelIds,
            ]);
        
        $response->assertRedirect(route('tasks.index'));
        
        $task = \App\Models\Task::where('name', 'Task with Labels')->first();
        $this->assertNotNull($task);
        $this->assertCount(3, $task->labels);
    }
}
