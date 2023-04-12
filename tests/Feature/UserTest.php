<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_admins_only_see_add_users(): void
    {


        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
          // Create a test user with a user role
          $user = User::factory()->create();
          $user2 = User::factory()->create();
          $user->roles()->attach(2);
          $user2->roles()->attach(1);
          $response = $this->actingAs($user)->get('/users/index');
          $response->assertStatus(403);
          $response = $this->actingAs($user2)->get('/users/index');
          $response->assertViewIs('user.index');
          $response->assertSee($user2->name);
          $response->assertSee($user->name);
    }
    public function test_create_users(): void
    {


        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
          // Create a test user with a user role
          $user = User::factory()->create();
          $user2 = User::factory()->create();
          $user->roles()->attach(2);
          $user2->roles()->attach(1);
          $response = $this->actingAs($user)->get('/users/create');
          $response->assertStatus(403);
          $response = $this->actingAs($user2)->get('/users/create');
          $response->assertViewIs('user.create');
          $params = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Call the store method of the controller with the request parameters
        $response = $this->post(route('user.store'), $params);

        // Assert that the response redirects to the users index page
        $response->assertRedirect('/users/index');

        // Assert that the new user was created in the database
        $this->assertDatabaseHas('users', ['name' => 'John Doe', 'email' => 'john@example.com']);


    }
}
