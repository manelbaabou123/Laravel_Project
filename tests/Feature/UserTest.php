<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_admin_can_access_users_index()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::create(['name' => 'admin']));

        $this->actingAs($admin)
            ->get(route('user.index'))
            ->assertStatus(200)
            ->assertSee($admin->name);
    }

    public function test_non_admin_cannot_access_index()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(2);
        $response = $this->actingAs($user)->get(route('user.index'));
        $response->assertStatus(403);
    }

    public function test_user_has_multiple_roles()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        $user = User::factory()->create();
        $user->roles()->attach(2);

        $admin = User::factory()->create();
        $admin->roles()->attach(1);
        $admin->roles()->attach(2);

        $this->actingAs($admin)->get(route('user.index'))
            ->assertSee('admin')
            ->assertSee('user');

        $this->assertDatabaseHas('role_user', [
            'role_id' => 1,
            'user_id' => $admin->id,
        ]);
        $this->assertDatabaseHas('role_user', [
            'role_id' => 2,
            'user_id' => $admin->id,
        ]);

        $this->assertEquals([2], $user->roles()->pluck('id')->toArray());
        $this->assertEquals([1, 2], $admin->roles()->pluck('id')->toArray());
    }

    public function test_admin_can_create_a_new_user()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        // Create an admin user
        $admin = User::factory()->create();
        $admin->roles()->attach(1);

        // Make a POST request to create a new user
        $response = $this->actingAs($admin)->post(route('user.store'), [
            'name' => 'New User name',
            'email' => 'Newuser@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 2,
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'New User name',
            'email' => 'Newuser@gmail.com',
        ]);

        // Assert that the user is redirected to the user index page after user creation
        $response->assertStatus(302);
        $response->assertRedirect(route('user.index'))
            ->assertSessionHas('status', 'User has been created successfully.');

    }

    public function test_admin_can_update_user_information()
    {
        // Create roles
        $role1= Role::create(['name' => 'admin']);
        $role2= Role::create(['name' => 'user']);

        // Create users
        $user = User::factory()->create();
        $admin = User::factory()->create();
        $admin->roles()->attach($role1->id);
        $user->roles()->attach($role2->id);

        // Acting as admin
        $this->actingAs($admin);

        // Make a PUT request to update the user information
        $response = $this->post(route('user.update', $user->id), [
            'name' => 'New Name',
            'email' => 'newemail@example.com',
            'password' => 'newpassword',
            'role_id' => $role1, // Assign admin role
        ]);

        // Assert that the user information was updated in the database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'newemail@example.com',
        ]);

        $this->assertDatabaseHas('role_user', [
            'role_id' => 1,
            'user_id' => $user->id,
        ]);

        // Assert that the response status code is 302 (redirect)
        $response->assertStatus(302);
        
        // Assert that the user is redirected to the user index page
        $response->assertRedirect(route('user.index'));

        // Assert that the user index page contains the updated user information
        $response = $this->get(route('user.index'));
        $response->assertSee('New Name');
        $response->assertSee('newemail@example.com');
        $response->assertSee('admin');
    }

    public function test_admin_auth_cannot_destroy_hisself()
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        // Assert that the auth admin exists in the database
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
             
        $response = $this->actingAs($admin)->get(route('user.destroy', ['user' => $admin]));

        $response->assertStatus(403);
    
        // Assert that the auth admin in the database
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
    
    public function test_admin_can_delete_user()
    {
        Role::create(['name' => 'admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach(1);

        // Create a user
        Role::create(['name' => 'user']);
        $user = User::factory()->create();
        $user->roles()->attach(2);

        // Call the delete method
        $response = $this->actingAs($admin)->get(route('user.destroy', ['user' => $user]));

        $response->assertStatus(302);
        $response->assertDontSee($user->name);
 
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
 
}
