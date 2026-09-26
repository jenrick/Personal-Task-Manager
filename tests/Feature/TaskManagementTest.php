<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_can_be_created_filtered_updated_and_deleted(): void
    {
        $this->post(route('tasks.store'), [
            'task_name' => 'Prepare presentation',
            'description' => 'Collect the project notes',
            'status' => 'pending',
            'due_date' => '2026-10-10',
        ])->assertRedirect(route('tasks.index'));

        $task = Task::where('task_name', 'Prepare presentation')->firstOrFail();
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'pending']);
        $this->get(route('tasks.index'))->assertOk()->assertSee('Prepare presentation');

        $this->post(route('tasks.store'), [
            'task_name' => 'Water the plants',
            'status' => 'pending',
        ]);

        $this->patch(route('tasks.status', $task), ['status' => 'completed'])
            ->assertRedirect(route('tasks.index'));
        $this->get(route('tasks.index', ['status' => 'pending']))
            ->assertSee('Water the plants')
            ->assertDontSee('Prepare presentation');
        $this->get(route('tasks.index', ['status' => 'completed']))
            ->assertSee('Prepare presentation');

        $this->put(route('tasks.update', $task), [
            'task_name' => 'Present the project',
            'description' => 'Final version',
            'status' => 'completed',
            'due_date' => '2026-10-11',
        ])->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'task_name' => 'Present the project']);

        $this->delete(route('tasks.destroy', $task))->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_task_name_is_required(): void
    {
        $this->from(route('tasks.create'))
            ->post(route('tasks.store'), ['status' => 'pending'])
            ->assertRedirect(route('tasks.create'))
            ->assertSessionHasErrors('task_name');

        $this->assertDatabaseCount('tasks', 0);
    }
}