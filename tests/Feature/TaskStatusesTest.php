<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskStatus;


class TaskStatusesTest extends TestCase
{
    public function test_task_statuses_screen_can_be_rendered(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertStatus(200);
    }

    public function test_create_task_status(): void
    {
        $response = $this->get(route('task_statuses.create'));
        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('task_statuses.create'));
        $response->assertStatus(200);

        $response = $this->post(route('task_statuses.store'), [
            'name' => 'Тестовый статус',
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('task_statuses.index'));

    }

    public function test_edit_task_status(): void
    {
        $status = new TaskStatus();
        $status->name = 'Тестовый статус';
        $status->save();


        $response = $this->get(route('task_statuses.edit', $status));
        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('task_statuses.edit', $status));
        $response->assertStatus(200);

        $response = $this->patch(route('task_statuses.update', $status), [
            'name' => 'Измененная тестовая метка',
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('task_statuses.index'));

    }

    public function test_delete_task_status(): void
    {
        $status = new TaskStatus();
        $status->name = 'Тестовый статус';
        $status->save();

        $response = $this->delete(route('task_statuses.destroy', $status));
        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->delete(route('task_statuses.destroy', $status));

        $response->assertStatus(302);

        $response->assertRedirect(route('task_statuses.index'));

    }
}
