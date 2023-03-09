<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private function registerUser() {
        $user = User::create([
            'name' => 'Vinicius',
            'email' => 'vini@gmail.com',
            'password' => Hash::make('1234@Allo'),
        ]);

        return JWTAuth::fromUser($user);
    }

    private function createTask() {
        return Task::create([
            'description' => 'Read college books',
            'title' => 'Read book',
            'user_id' => 1,
        ]);
    }

    public function test_create_task()
    {
        $token = $this->registerUser();

        $response = $this->post('/api/tasks/create', [
            'title' => 'Read book',
            'description' => 'Read college books',
        ], [ 'Authorization' => "Bearer $token" ]);

        $response->assertStatus(201);
    }

    public function test_update_task()
    {
        $token = $this->registerUser();
        $this->createTask();

        $response = $this->put('/api/tasks/1/update', [
            'title' => 'Read book',
            'description' => 'Read school books',
        ], [ 'Authorization' => "Bearer $token" ]);

        $response->assertStatus(200);
    }

    public function test_update_unauthorized_task()
    {
        $token = $this->registerUser();
        $this->createTask();

        $response = $this->put('/api/tasks/2/update', [
            'title' => 'Read book',
            'description' => 'Read school books',
        ], [ 'Authorization' => "Bearer $token" ]);

        $response->assertStatus(403);
    }

    public function test_delete_task()
    {
        $token = $this->registerUser();
        $this->createTask();

        $response = $this->delete('/api/tasks/1/delete', [], [ 'Authorization' => "Bearer $token" ]);

        $response->assertStatus(200);
    }

    public function test_delete_unauthorized_task()
    {
        $token = $this->registerUser();
        $this->createTask();

        $response = $this->delete('/api/tasks/2/delete', [], [ 'Authorization' => "Bearer $token" ]);

        $response->assertStatus(403);
    }

    public function test_details_task()
    {
        $token = $this->registerUser();
        $task = $this->createTask();

        $response = $this->get('/api/tasks/1/details', [ 'Authorization' => "Bearer $token" ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'title' => $task->title
            ]);
    }

    public function test_details_unauthorized_task()
    {
        $token = $this->registerUser();
        $this->createTask();

        $response = $this->get('/api/tasks/2/details', [ 'Authorization' => "Bearer $token" ]);

        $response->assertStatus(403);
    }

    public function test_list_tasks()
    {
        $token = $this->registerUser();
        $this->createTask();

        $response = $this->get('/api/tasks/list', [ 'Authorization' => "Bearer $token" ]);

        $response->assertStatus(200);
    }
}
