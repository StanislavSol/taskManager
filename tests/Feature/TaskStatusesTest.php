<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\TaskStatus;


class TaskStatusesTest extends TestCase
{
    use RefreshDatabase;
    private $status;

    public function setUp(): void
    {
        parent::setUp();
        $this->status = new TaskStatus();
        $this->status->name = 'Тестовый статус';
        $this->status->save();
        $this->user = User::factory()->create();
    }

    public function test_task_statuses_screen_can_be_rendered(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertStatus(200);
    }

    public function test_create_task_status(): void
    {
        $response = $this->get(route('task_statuses.create'));
        $response->assertStatus(403);

        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('task_statuses.create'));
        $response->assertStatus(200);

        $response = $this->post(route('task_statuses.store'), [
            'name' => 'Новый тестовый статус',
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('task_statuses.index'));

    }

    public function test_edit_task_status(): void
    {

        $response = $this->get(route('task_statuses.edit', $this->status));
        $response->assertStatus(403);

        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('task_statuses.edit', $this->status));
        $response->assertStatus(200);

        $response = $this->patch(route('task_statuses.update', $this->status), [
            'name' => 'Измененная тестовая метка',
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('task_statuses.index'));

    }

    public function test_delete_task_status(): void
    {

        $response = $this->delete(route('task_statuses.destroy', $this->status));
        $response->assertStatus(403);

        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response = $this->delete(route('task_statuses.destroy', $this->status));

        $response->assertStatus(302);

        $response->assertRedirect(route('task_statuses.index'));

    }
}
