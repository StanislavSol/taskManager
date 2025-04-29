<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskStatusesTest extends TestCase
{
    public function test_task_statuses_screen_can_be_rendered(): void
    {
        $response = $this->get(route('task_statuses.index'));
        $response->assertStatus(200);
    }
}
