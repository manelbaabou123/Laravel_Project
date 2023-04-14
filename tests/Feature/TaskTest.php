<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseTransactions;

    public function testIndexViewReturnsTasksBelongingToConnectedUserAndProject()
    {
        // Create a admin and a project then attach user to project
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $admin=User::factory()->create();
        $admin->roles()->attach(1);
        $user = User::factory()->create();
        $user->roles()->attach(2);
        $project = Project::factory()->create(['creator_id' => $admin->id]);
        $project->users()->attach($user->id);

        // Create two tasks belonging to the project for the user
        $task1 = Task::factory()->create(['project_id' => $project->id,'creator_id' => $admin->id]);
        $task2 = Task::factory()->create(['project_id' => $project->id,'creator_id' => $admin->id]);
        $response = $this->actingAs($user)->get(route('task.index', ['project' => $project->id]));

        // Assert that the response has a 200 status code
        $response->assertStatus(200);
        
       $task1->users()->attach($user->id);
       // $user->tasks()->attach($task1);
        // Create a task belonging to a different project
        $otherProject = Project::factory()->create(['creator_id' => $admin->id]);
        $otherTask = Task::factory()->create(['project_id' => $otherProject->id,'creator_id' => $admin->id]);


        // Call the task index endpoint for the project
        $response = $this->actingAs($user)->get(route('task.index', ['project' => $project->id]));

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

    }
    /** @test */
    public function index_view_only_returns_tasks_belongs_to_connected_user_and_given_project()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $admin = User::factory()->create();
        $user->roles()->attach(2);
        $user2->roles()->attach(2);
        $admin->roles()->attach(1);

        $project = Project::factory()->create();
        $project2 = Project::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);
        $task2 = Task::factory()->create([
            'user_id' => $user2->id,
            'project_id' => $project->id,
        ]);



        $this->actingAs($user)
            ->get(route('task.index', ['project' => $project]))
            ->assertViewIs('task.index')
            ->assertViewHas('tasks', function ($tasks) use ($task) {
                return $tasks->contains($task);
            });
        $this->actingAs($user)->get(route('task.index', ['project' => $project]))
        ->assertViewIs('task.index')
        ->assertDontSee($task2);

        $this->actingAs($user)->get(route('task.index', ['project' => $project2]))
        ->assertViewIs('task.index')
        ->assertDontSee($task2)
        ->assertDontSee($task);

        $this->actingAs($user2)
            ->get(route('task.index', ['project' => $project]))
            ->assertViewIs('task.index')
            ->assertViewHas('tasks', function ($tasks) use ($task2) {
                return $tasks->contains($task2);
            });
        $this->actingAs($user2)->get(route('task.index', ['project' => $project]))
        ->assertViewIs('task.index')
        ->assertDontSee($task);
    }

    /** @test */
    public function a_task_can_be_only_created_by_an_admin_or_a_user_that_is_affected_to_given_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        // Admin can create a task
        $this->actingAs($admin)
            ->post(route('task.store', ['project' => $project]), [
                'name' => 'Task 1',
                'description' => 'Task description',
                'user_id' => $user->id,
            ])
            ->assertRedirect(route('task.index', ['project' => $project]))
            ->assertSessionHas('status', 'Task has been successfully created.');

        $this->assertDatabaseHas('tasks', [
            'name' => 'Task 1',
            'description' => 'Task description',
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        // User who is affected to the project can create a task
        $this->actingAs($user)
            ->post(route('task.store', ['project' => $project]), [
                'name' => 'Task 2',
                'description' => 'Task description',
                'user_id' => $user->id,
            ])
            ->assertRedirect(route('task.index', ['project' => $project]))
            ->assertSessionHas('status', 'Task has been successfully created.');

        $this->assertDatabaseHas('tasks', [
            'name' => 'Task 2',
            'description' => 'Task description',
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        // User who is not affected to the project cannot create a task
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser)
            ->post(route('task.store', ['project' => $project]), [
                'name' => 'Task 3',
                'description' => 'Task description',
                'user_id' => $otherUser->id,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('tasks', [
            'name' => 'Task 3',
            'description' => 'Task description',
            'project_id' => $project->id,
            'user_id' => $otherUser->id,
        ]);
    }
}