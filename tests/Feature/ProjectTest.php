<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;

class ProjectTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_show_only_projects_that_belongs_to_authed_user()
    {
        Role::create(['name' => 'admin']);
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

        $task1 = Task::factory()->create(['user_id' => $user->id, 'project_id' => $project1->id]);
        $task2 = Task::factory()->create(['user_id' => $user->id, 'project_id' => $project2->id]);
        $task3 = Task::factory()->create(['user_id' => $user2->id, 'project_id' => $project3->id]);

        // Authenticate the test user and call the index method of the controller
        $response = $this->actingAs($user)->get('/tasks/index');

        // Assert that the returned list of projects only contains the projects associated with the authenticated user
        $response->assertSee($project1->name);
        $response->assertSee($project2->name);
        $response->assertDontSee($project3->name);

        // Authenticate the test admin user and call the index method of the controller
        $response = $this->actingAs($admin)->get('/projects/index');

        // Assert that the returned list of projects contains all projects
        $response->assertSee($project1->name);
        $response->assertSee($project2->name);
        $response->assertSee($project3->name);

        $response = $this->actingAs($user2)->get('/tasks/index');
        $response->assertSee($project3->name);
        $response->assertDontSee($project1->name);
        $response->assertDontSee($project2->name);
    }

    public function test_only_admins_can_access_create_project_page()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(2);

        $admin = User::factory()->create();
        $admin->roles()->attach(1);
        //test admin user
        $this->actingAs($admin);
        $response = $this->get('/projects/create');
        $response->assertViewIs('project.create');
        //test non admin user

        $this->actingAs($user);
        $response = $this->get('/projects/create');
        $response->assertStatus(403);
    }
    public function test_authorized_to_Store_Project()
    {
        // Create a user with admin role
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $admin = User::factory()->create();
        $admin->roles()->attach(1);

        // Create a project
        $projectData = [
            'name' => 'Test Project',
            'description' => 'This is a test project',
        ];
        $response = $this->actingAs($admin)->post(route('project.store'), $projectData);
        // Assert that the user is redirected to the project index page
        $response->assertRedirect('/projects/index');
        // Assert that the project was created in the database
        $this->assertDatabaseHas('projects', $projectData);
    }
    public function test_non_authorized_to_Store_Project()
    {
        // Create a user with user role
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(2);

        // Create a project
        $projectData = [
            'name' => 'Test Project2',
            'description' => 'This is a test project',
        ];
        $response = $this->actingAs($user)->post(route('project.store'), $projectData);
        $response->assertStatus(403);
        // Assert that the project was not created in the database
        $this->assertDatabaseMissing('projects', $projectData);
    }
    public function testDeleteAuthorized()
    {
        // Create a test user with an 'admin' role
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(1);

        // Authenticate the test user
        $this->actingAs($user);

        // Create a test project
        $project = Project::factory()->create();

        // Call the delete method
        $response = $this->get(route('project.destroy', $project));

        // Assert that the response redirects to the projects index page
        $response->assertRedirect('/projects/index');

        // Assert that the project was deleted from the database
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function testDeleteUnauthorized()
    {
        // Create a test user with a 'user' role
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(2);

        // Authenticate the test user
        $this->actingAs($user);

        // Create a test project
        $project = Project::factory()->create();
        $projectData = [
            'name' => 'Test Project',
            'description' => 'This is a test project',
        ];

        // Call the delete method of the controller
        $response = $this->get(route('project.destroy', $project));

        // Assert that the response status code is 403 Forbidden
        $response->assertStatus(403);

        // Assert that the project was not deleted from the database
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }
    public function test_only_admins_can_access_update_project_page(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(2);

        $admin = User::factory()->create();
        $admin->roles()->attach(1);
        //test admin user
        $this->actingAs($admin);
        // Create a test project
        $project = Project::factory()->create();

        $response = $this->actingAs($admin)->get(route('project.edit', $project));
        // Assert that the response view is the 'project.edit' view
        $response->assertViewIs('project.update');

        // Assert that the view contains the project data
        $response->assertViewHas('project', function ($data) use ($project) {
            return $data->id === $project->id;
        });

        //try with non admin user
        $response = $this->actingAs($user)->get(route('project.edit', $project));

        $response->assertStatus(403);
        // $response->assertSessionHasErrors(['message' => 'You are not authorized to access this page.']);
    }
    public function test_admin_can_update_a_project()
    {
        // Create a test user with the 'admin' role
        $admin = User::factory()->create();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $admin->roles()->attach(1);

        // Create a test project
        $project = Project::factory()->create();

        // Make a request to the update project endpoint with new project data
        $newData = [
            'name' => 'New Project Name',
            'description' => 'New Project Description',
        ];
        $response = $this->actingAs($admin)
            ->post(route('project.update', $project), $newData);

        // Assert that the response status is 302 (redirect)
        $response->assertStatus(302);

        // Assert that the project data has been modified in the database
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'New Project Name',
            'description' => 'New Project Description',
        ]);
    }

    public function test_regular_user_cannot_update_project()
    {
        $user = User::factory()->create();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $user->roles()->attach(2);
        // Create a project
        $project = Project::factory()->create();

        // Attempt to update the project as a regular user
        $newData = [
            'name' => 'New Project Name',
            'description' => 'New Project Description'
        ];

        $response = $this->actingAs($user)
            ->post(route('project.update', $project), $newData);

        // Assert that the user is redirected to the project index page
        $response->assertStatus(403);

        // Assert that the project has not been modified in the database
        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
            'name' => $newData['name'],
            'description' => $newData['description']
        ]);
    }
}
