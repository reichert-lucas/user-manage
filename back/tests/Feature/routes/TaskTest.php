<?php

namespace Tests\Feature;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class TaskTest extends TestCase
{
    /** @test */
    public function should_create_task(): void
    {
        $this->freezeSecond();
        $taskName = 'test task';
        $this->actingAs(User::first())->put(route('tasks.store'), [
            'name' => $taskName
        ]);

        $this->assertTrue(
            Task::where('name', $taskName)
                ->whereDate('created_at', Carbon::now())
                ->exists()
        );
    }

    /** @test */
    public function should_change_task_status(): void
    {
        $task = Task::create(['name' => 'test task']);

        $this->actingAs(User::first())->put(route('tasks.update', ['task' => $task->id]), [
            'status_id' => TaskStatusEnum::CONCLUDED->value
        ]);
        $task->refresh();

        $this->assertEquals($task->status_id, TaskStatusEnum::CONCLUDED);
    }

    /** @test */
    public function should_change_the_completion_date_when_status_changes_to_completed(): void
    {
        $this->freezeSecond();
        $task = Task::create(['name' => 'test task']);

        $this->actingAs(User::first())->put(route('tasks.update', ['task' => $task->id]), [
            'status_id' => TaskStatusEnum::CONCLUDED->value
        ]);
        $task->refresh();

        $this->assertEquals($task->concluded_at, Carbon::now());
    }

    /** @test */
    public function should_change_task_name(): void
    {
        $newName = 'test task edited';
        $task = Task::create(['name' => 'test task']);

        $this->actingAs(User::first())->put(route('tasks.update', ['task' => $task->id]), [
            'name' => $newName
        ]);
        $task->refresh();

        $this->assertEquals($task->name, $newName);
    }

    /** @test */
    public function should_filter_task(): void
    {
        $task = Task::create([
            'name' => 'test task',
            'status_id' => TaskStatusEnum::DOING
        ]);

        $res = $this->actingAs(User::first())->get(route('tasks.index', [
            'id' => $task->id,
            'search' => $task->name,
            'status_ids' => [
                TaskStatusEnum::DOING->value
            ]
        ]));

        $this->assertEquals($res->json()['data'][0]['id'], $task->id);
    }

    /** @test */
    public function should_delete_task(): void
    {
        $task = Task::create([
            'name' => 'test task',
            'status_id' => TaskStatusEnum::DOING
        ]);

        $res = $this->actingAs(User::first())->delete(route('tasks.destroy', [
            'task' => $task->id
        ]));

        $res->assertStatus(204);
        $this->assertNull(Task::find($task->id));
    }

    /** @test */
    public function should_show_task(): void
    {
        $task = Task::create([
            'name' => 'test task',
            'status_id' => TaskStatusEnum::DOING
        ]);

        $res = $this->actingAs(User::first())->get(route('tasks.show', [
            'task' => $task->id
        ]));

        $res->assertStatus(200);
        $this->assertEquals($res->json()['id'], $task->id);
    }

    /** @test */
    public function should_return_not_found_when_task_dont_exist(): void
    {
        $res = $this->actingAs(User::first())->get(route('tasks.show', [
            'task' => -1
        ]));

        $res->assertStatus(404);
    }
}
