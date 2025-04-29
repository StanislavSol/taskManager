<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Label;
use App\Models\User;

class LabelsTest extends TestCase
{
    public function test_labels_screen_can_be_rendered(): void
    {
        $response = $this->get(route('labels.index'));
        $response->assertStatus(200);
    }

    public function test_create_label(): void
    {
        $response = $this->get(route('labels.create'));
        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('labels.create'));
        $response->assertStatus(200);

        $response = $this->post(route('labels.store'), [
            'name' => 'Тестовая метка',
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('labels.index'));

    }

    public function test_edit_label(): void
    {
        $label = new Label();
        $label->name = 'Тестовая метка';
        $label->save();


        $response = $this->get(route('labels.edit', $label));
        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get(route('labels.edit', $label));
        $response->assertStatus(200);

        $response = $this->patch(route('labels.update', $label), [
            'name' => 'Измененная тестовая метка',
        ]);
        $response->assertStatus(302);

        $response->assertRedirect(route('labels.index'));

    }

    public function test_delete_label(): void
    {
        $label = new Label();
        $label->name = 'Тестовая метка';
        $label->save();

        $response = $this->delete(route('labels.destroy', $label));
        $response->assertStatus(403);

        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->delete(route('labels.destroy', $label));

        $response->assertStatus(302);

        $response->assertRedirect(route('labels.index'));

    }
}
