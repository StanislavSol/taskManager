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
    public function test_task_screen_can_be_rendered(): void
    {
        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200);
    }

    public function test_create_task(): void
    {
        $status = new TaskStatus();
        $status->name = 'Тестовый статус';
        $status->save();

        $response = $this->get(route('tasks.create'));
        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('tasks.create'));
        $response->assertStatus(200);

        $response = $this->post(route('tasks.store'), [
            'name' => 'Тестовая задача',
            'status_id' => (string) $status->id,
            'creator_by_id' => $user->id,
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('tasks.index'));

    }

    public function test_edit_task(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $status = new TaskStatus();
        $status->name = 'Тестовый статус';
        $status->save();

        $task = new Task();
        $task->name = 'Тестовая задача';
        $task->status_id = $status->id;
        $task->creator_by_id = $user->id;
        $task->save();

        $response = $this->get(route('tasks.edit', $task));
        $response->assertStatus(200);

        $response = $this->patch(route('tasks.update', $task), [
            'name' => 'Измененная задача',
            'status_id' => (string) $status->id,
            'creator_by_id' => $user->id,

        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('tasks.index'));

        $response = $this->actingAs($user)->post('/logout');

        $response = $this->get(route('tasks.edit', $task));
        $response->assertStatus(403);

    }

    public function test_delete_task(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $status = new TaskStatus();
        $status->name = 'Тестовый статус';
        $status->save();

        $task = new Task();
        $task->name = 'Тестовая задача';
        $task->status_id = $status->id;
        $task->creator_by_id = $user->id;
        $task->save();

        $response = $this->actingAs($user)->post('/logout');

        $response = $this->delete(route('tasks.destroy', $task));
        $response->assertStatus(403);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertStatus(302);

        $response->assertRedirect(route('tasks.index'));

    }
}
