<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TaskService();
    }

    public function test_create_task_persists_to_database(): void
    {
        $task = $this->service->createTask([
            'title'    => 'Service test task',
            'status'   => 'pending',
            'priority' => 'medium',
        ]);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertDatabaseHas('tasks', ['title' => 'Service test task']);
    }

    public function test_update_task_changes_status(): void
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $updated = $this->service->updateTask($task, ['status' => 'completed', 'title' => $task->title, 'priority' => $task->priority]);

        $this->assertEquals('completed', $updated->status);
    }

    public function test_delete_task_removes_from_database(): void
    {
        $task = Task::factory()->create();

        $this->service->deleteTask($task);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_get_stats_returns_correct_counts(): void
    {
        Task::factory()->count(2)->create(['status' => 'pending']);
        Task::factory()->count(1)->create(['status' => 'in_progress']);
        Task::factory()->count(3)->create(['status' => 'completed']);

        $stats = $this->service->getStats();

        $this->assertEquals(6, $stats['total']);
        $this->assertEquals(2, $stats['pending']);
        $this->assertEquals(1, $stats['in_progress']);
        $this->assertEquals(3, $stats['completed']);
    }

    public function test_get_paginated_tasks_filters_by_status(): void
    {
        Task::factory()->count(3)->create(['status' => 'pending']);
        Task::factory()->count(2)->create(['status' => 'completed']);

        $result = $this->service->getPaginatedTasks(['status' => 'pending']);

        $this->assertEquals(3, $result->total());
    }
}
