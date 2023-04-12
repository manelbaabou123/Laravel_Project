<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Role;
class ProjectTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {      Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
          // Create a test user with a user role
          $user = User::factory()->create();
          $user2 = User::factory()->create();
          $user->roles()->attach(2);
          $user2->roles()->attach(2);

          // Create a test admin user with an admin role
          $admin = User::factory()->create();
          $admin->roles()->attach(1);

          // Create some projects and associate them with the test user
          $project1 = Project::factory()->create();
          $project2 = Project::factory()->create();
          $project3 = Project::factory()->create();

          $task1= Task::factory()->create(['user_id' => $user->id,'project_id'=>$project1->id]);
          $task2= Task::factory()->create(['user_id' => $user->id,'project_id'=>$project2->id]);
          $task3= Task::factory()->create(['user_id' => $user2->id,'project_id'=>$project3->id]);

          // Authenticate the test user and call the index method of the controller
          $response = $this->actingAs($user)->get('/projects');

          // Assert that the returned list of projects only contains the projects associated with the authenticated user
          $response->assertSee($project1->name);
          $response->assertSee($project2->name);
          $response->assertDontSee($project3->name);

          // Authenticate the test admin user and call the index method of the controller
          $response = $this->actingAs($admin)->get('/projects');

          // Assert that the returned list of projects contains all projects
          $response->assertSee($project1->name);
          $response->assertSee($project2->name);
          $response->assertSee($project3->name);

          $response = $this->actingAs($user2)->get('/projects');
          $response->assertSee($project2->name);
          $response->assertDontSee($project1->name);
          $response->assertDontSee($project3->name);

    }
}
