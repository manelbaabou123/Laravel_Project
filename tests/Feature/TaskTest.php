<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // protected $admin;
    // protected $regularUser;
    // protected $project;
    // protected $task;

    // public function setUp(): void
    // {
    //     parent::setUp();

    //     // Create roles
    //     Role::create(['name' => 'admin']);
    //     Role::create(['name' => 'regular']);

    //     // Create admin user
    //     $this->admin = User::factory()->create();
    //     $this->admin->roles()->attach(Role::where('name', 'admin')->first());

    //     // Create regular user
    //     $this->regularUser = User::factory()->create();
    //     $this->regularUser->roles()->attach(Role::where('name', 'regular')->first());

    //     // Create project
    //     $this->project = Project::factory()->create();

    //     // Create task for the project assigned to the regular user
    //     $this->task = Task::factory()->create([
    //         'project_id' => $this->project->id,
    //         'user_id' => $this->regularUser->id
    //     ]);
    // }

    public function test_admin_can_see_all_tasks_with_project_and_user_name()
    {
        // Create an admin user
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach(1);

        // Create a project
        $project = Project::factory()->create();

        // Create a user
        Role::create(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(2);

        // Create tasks associated with the project and user
        $tasks = Task::factory()->count(3)->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        // Authenticate as the admin user
        $this->actingAs($admin);

        // Make a GET request to the tasks index page
        $response = $this->get(route('task.index'));

        // Assert that the response contains the task titles and project and user information
        foreach ($tasks as $task) {
            $response->assertSee($task->title)
                ->assertSee($task->project->title)
                ->assertSee($task->user->name);
        }
    }

    public function test_admin_can_create_a_new_task_while_assiging_it_to_his_project_and_which_user()
    {
        // Create an admin user
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach(1);

        // Create a project
        $project = Project::factory()->create();

        // Create a user
        Role::create(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(2);

        // Authenticate as the admin user
        $this->actingAs($admin);

        // Make a POST request to create a new task
        $response = $this->post(route('task.store'), [
            'name' => 'New Task name',
            'description' => 'New Task description',
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        // Assert that the task was created with the correct information
        $this->assertDatabaseHas('tasks', [
            'name' => 'New Task name',
            'description' => 'New Task description',
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        // Assert that the user is redirected to the task index page after task creation
        $response->assertRedirect(route('task.index'))
            ->assertSessionHas('status', 'Task has been created successfully.');
    }

    public function test_admin_can_update_task_information_for_assigned_user()
    {
        // Create an admin user
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        // Create a project
        $project = Project::factory()->create();

        // Create a user
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'user')->first());

        // Create task for the project assigned to the regular user
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'name' => 'Original Task Name',
            'description' => 'Original Task desc'
        ]);

        // Simulate the admin navigating to the task edit page
        $response = $this->actingAs($admin)
            ->get(route('task.edit', $task));

        // Check that the admin can access the edit page
        $response->assertOk();

        // Simulate the admin updating the task
        $updatedTaskData = [
            'name' => 'Updated Task Name',
            'description' => $task->description,
            'project_id' => $task->project_id,
            'user_id' => $user->id
        ];

        $response = $this->actingAs($admin)
            ->post(route('task.update', $task), $updatedTaskData);

        // Reload the task record from the database
        $task = $task->fresh();

        // Check that the admin is redirected to the task index page after task update
        $response->assertRedirect(route('task.index'))
            ->assertSessionHas('status', 'Task has been successfully modified.');

        // Check that the task was updated successfully
        $this->assertEquals('Updated Task Name', $task->name);
    }

    public function test_admin_can_delete_task()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach(1);

        // Create a project
        $project = Project::factory()->create();

        // Create a user

        Role::create(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(2);
    
        // Create task for the project assigned to the regular user
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'name' => 'Task Name',
            'description' => 'Task desc'
        ]);

        // Call the delete method
        $response = $this->actingAs($admin)->get(route('task.destroy', ['task' => $task]));

        $response->assertDontSee($task->name);
 
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'name' => $task->name
        ]);

    }

    public function test_user_can_only_see_his_tasks_affected_by_project()
    {
        // create a user and some tasks
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $project = Project::factory()->create();
        $task1 = Task::factory()->create(['user_id' => $user->id, 'project_id' => $project->id]);
        $task2 = Task::factory()->create(['user_id' => $user->id, 'project_id' => $project->id]);
        $task3 = Task::factory()->create(['user_id' => $otherUser->id, 'project_id' => $project->id]);

        // login as the user and make a request to the index page
        $this->actingAs($user);
        $response = $this->get(route('task.index'));

        // check that the response contains the user's tasks but not other user's task
        $response->assertOk();
        $response->assertSee($task1->name);
        $response->assertSee($task2->name);
        $response->assertDontSee($task3->name);
    }

    public function test_user_can_create_task_assigned_to_themselves_and_valid_project()
    {
        // Create a user
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'user')->first());
        $this->actingAs($user);

        // Create a project
        $project = Project::factory()->create();

        // Try to create a task assigned to the authenticated user and valid project
        $response = $this->post(route('task.store'), [
            'name' => 'Task Name',
            'description' => 'Task Description',
            'project_id' => $project->id,
            'user_id' => $user->id // Assigned to the authenticated user
        ]);

        // Assert that the response status code is 302 (redirect)
        $response->assertStatus(302);
        $response->assertRedirect(route('task.index'));
        $response->assertSessionHas('status', 'Task has been created successfully.');

        $this->assertDatabaseHas('tasks', [
            'name' => 'Task Name',
            'description' => 'Task Description',
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);
    }

    public function test_user_can_update_task_information()
    {
        // Create a user and log in
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'user')->first());
        $this->actingAs($user);

        // Create a project
        $project = Project::factory()->create();
        $project1 = Project::factory()->create();


        // Create a task
        $task = Task::factory()->create([
            'name' => 'Task Name',
            'description' => 'Task Description',
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);

        // Make a POST request to update the task
        $response = $this->post(route('task.update', $task->id), [
            'name' => 'Updated Task Name',
            'description' => 'Updated Task Description',
            'project_id' => $project1->id,
            'user_id' => $user->id,
        ]);
        
        // Assert that the task was updated in the database
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated Task Name',
            'description' => 'Updated Task Description',
            'project_id' => $project1->id,
            'user_id' => $user->id,
        ]);
       
        // Assert that the user was redirected to the task index page with a success message
        $response->assertRedirect(route('task.index'));
        $response->assertSessionHas('status', 'Task has been successfully modified.');
       
    }
}
