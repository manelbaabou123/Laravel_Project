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

    public function index_view_only_returns_tasks_belongs_to_connected_user_and_given_project()
    {
        // Create a admin and a project then attach user to project
       $ok1= Role::create(['name' => 'admin']);
        $ok2 = Role::create(['name' => 'user']);

        $admin=User::factory()->create(['role_id' => $ok1->id]);
        $user = User::factory()->create(['role_id' => $ok2->id]);
        $user2 = User::factory()->create(['role_id' => $ok1->id]);
        $project = Project::factory()->create(['creator_id' => $admin->id]);
        $project->users()->attach($user->id);
       // Route::get('/create', [ProjectController::class, 'create'])->name('project.create');

        // Create two tasks belonging to the project for the user
        $task1 = Task::factory()->create(['project_id' => $project->id,'creator_id' => $admin->id]);
        $task2 = Task::factory()->create(['project_id' => $project->id,'creator_id' => $admin->id]);

        //
        $task1->users()->attach($user);
        //$user->tasks()->attach($task1);
        $task2->users()->attach($user2->id);

        // Create a task belonging to a different project
        $otherProject = Project::factory()->create(['creator_id' => $admin->id]);
        $otherTask = Task::factory()->create(['project_id' => $otherProject->id,'creator_id' => $admin->id]);

        $otherTask->users()->attach($user2->id);

        // Call the task index endpoint for the project
        $response = $this->actingAs($user)->get(route('task.index'));

        $response->assertStatus(200);
        $response->assertSee($task1->name);
        $response->assertSee($task1->description);
        $response->assertDontSee($task2->name);
        $response->assertDontSee($task2->description);

        $response = $this->actingAs($user2)->get(route('task.index'));


        $response->assertStatus(200);
        $response->assertSee($task2->name);
        $response->assertSee($task2->description);
        $response->assertSee($otherTask->name);
        $response->assertSee($otherTask->description);
        $response->assertDontSee($task1->name);
        $response->assertDontSee($task1->description);

    }

    /** @test */
    public function a_task_can_be_only_created_by_an_admin_or_a_user_that_is_affected_to_given_project()
    {
        $ok1= Role::create(['name' => 'admin']);
        $ok2 = Role::create(['name' => 'user']);

        $admin=User::factory()->create(['role_id' => $ok1->id]);

        $user = User::factory()->create(['role_id' => $ok2->id]);
        $user2 = User::factory()->create(['role_id' => $ok2->id]);

        $project = Project::factory()->create(['creator_id' => $admin->id]);
        $project->users()->attach($user->id);

        // Admin can create a task
        $response=$this->actingAs($admin)
            ->post(route('task.store', [
                'project_id' => $project->id,
                'name' => 'Task 1',
                'description' => 'Task description',
                'creator_id' => $admin->id,
            ]));

           //$response->assertRedirect(route('task.index'));
           //$response->assertSessionHas('status', 'Task has been created successfully.');

        $this->assertDatabaseHas('tasks', [
            'name' => 'Task 1',
            'description' => 'Task description',
            'project_id' => $project->id,
            'creator_id' => $admin->id,
        ]);

        // User who is affected to the project can create a task
        //dd($user->projects()->wherePivot('project_id', $project->id)->exists());
        $this->actingAs($user)
            ->post(route('task.store', [
                'name' => 'Task 2',
                'project_id' => $project->id,
                'description' => 'Task description',
                'creator_id' => $user->id,
            ]));

           // $response->assertRedirect(route('task.index'));
            //$response->assertSessionHas('status', 'Task has been created successfully.');
        $this->assertDatabaseHas('tasks', [
            'name' => 'Task 2',
            'description' => 'Task description',
            'project_id' => $project->id,
            'creator_id' => $user->id,
        ]);

        // User who is not affected to the project cannot create a task
        $otherUser = User::factory()->create(['role_id' => $ok2->id]);
        $this->actingAs($otherUser)
            ->post(route('task.store' ,[
                'name' => 'Task 3',
                'project_id' => $project->id,
                'description' => 'Task description',
                'creator_id' => $otherUser->id,
            ]))
            ->assertForbidden();

        $this->assertDatabaseMissing('tasks', [
            'name' => 'Task 3',
            'description' => 'Task description',
            'project_id' => $project->id,
            'creator_id' => $otherUser->id,
        ]);
    }
}
