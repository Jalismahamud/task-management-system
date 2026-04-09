<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_tasks(): void
    {
        Task::factory()->count(3)->create();

        $this->get(route('tasks.index'))
            ->assertOk()
            ->assertViewIs('tasks.index')
            ->assertViewHas('tasks');
    }

    public function test_create_page_loads(): void
    {
        $this->get(route('tasks.create'))->assertOk();
    }

    public function test_store_creates_task_and_redirects(): void
    {
        $data = [
            'title'       => 'Write unit tests',
            'description' => 'Cover all critical paths',
            'status'      => 'pending',
            'priority'    => 'high',
            'due_date'    => now()->addDays(5)->format('Y-m-d'),
        ];

        $this->post(route('tasks.store'), $data)
            ->assertRedirect(route('tasks.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tasks', ['title' => 'Write unit tests']);
    }

    public function test_store_fails_validation_without_title(): void
    {
        $this->post(route('tasks.store'), ['title' => '', 'status' => 'pending', 'priority' => 'low'])
            ->assertSessionHasErrors('title');
    }

    public function test_show_displays_task(): void
    {
        $task = Task::factory()->create();

        $this->get(route('tasks.show', $task))
            ->assertOk()
            ->assertViewIs('tasks.show')
            ->assertSee($task->title);
    }

    public function test_edit_page_loads_with_task_data(): void
    {
        $task = Task::factory()->create();

        $this->get(route('tasks.edit', $task))
            ->assertOk()
            ->assertSee($task->title);
    }

    public function test_update_modifies_task_and_redirects(): void
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $this->put(route('tasks.update', $task), [
            'title'    => 'Updated title',
            'status'   => 'completed',
            'priority' => 'low',
        ])->assertRedirect(route('tasks.index'))
          ->assertSessionHas('success');

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'completed']);
    }

    public function test_destroy_deletes_task_and_redirects(): void
    {
        $task = Task::factory()->create();

        $this->delete(route('tasks.destroy', $task))
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_index_filters_by_status(): void
    {
        Task::factory()->create(['status' => 'pending',   'title' => 'Task A']);
        Task::factory()->create(['status' => 'completed', 'title' => 'Task B']);

        $this->get(route('tasks.index', ['status' => 'pending']))
            ->assertSee('Task A')
            ->assertDontSee('Task B');
    }
}
