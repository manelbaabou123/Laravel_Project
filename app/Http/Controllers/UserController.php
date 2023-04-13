<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Project;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if(Auth()->user()->hasRoleAdmin())
            return view('user.index', ['users' => User::with("roles")->paginate(10)]);
            return redirect()->back()->with('status', '************** YOU DO NOT HAVE ACCESS ! ******************');

        } catch (\Exception $ex) {
            Log::critical("index error user".$ex->getMessage());
            abort(500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         try {
            if (Auth()->user()->hasRoleAdmin()){
                return view('user.create', ['roles' => Role::select('id','name')->get()]);
            }
            return redirect()->back()->with('status', '************** YOU DO NOT HAVE ACCESS ! ******************');

        } catch (\Exception $ex) {
            Log::critical("create error user".$ex->getMessage());
            abort(500);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try {
            $request->validate([
                'role_id' => 'required',
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
            ]);
          
         $user =   User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
         ]);
         $user->roles()->attach($request->role_id);

            return redirect()->route('user.index')->with('status', 'User has been created successfully.');
        } catch (\Exception $ex) {
            Log::critical("store error User".$ex->getMessage());
            abort(500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //return view('tasks.index', ['tasks' =>Auth()->user()->tasks ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        try {
            if(Auth()->user()->hasRoleAdmin()){
                return view('user.update', ['roles' => Role::select('id','name')->get(),'user'=> $user]);
            }
        return  redirect()->route("user.index")->with('status', '************ YOU DO NOT HAVE ACCESS TO UPDATE USERS ! *************');
        } catch (\Exception $ex) {
            Log::critical("edit error user".$ex->getMessage());
            abort(500);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
       // dd($request->all());
         try {
            $request->validate([
                'role_id' => 'required',
                'name' => 'required',
                'email' => 'required|unique:users,email,'.$user->id,
                'password' => 'required',
            ]);
      
            $user->update([
                'role_id' => $request->role_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            
            $user->roles()->sync($request->role_id);

            return redirect()->route('user.index')->with('status', 'User has been successfully modified.');

            } catch (\Exception $ex) {
                Log::critical("update error user".$ex->getMessage());
                abort(500);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
      
            if (Auth()->user()->hasRoleAdmin())
            {
            $user->roles()->detach($user->roles);
            Auth::logout();
            $user->delete();
            return redirect()->route('user.index')->with('status', 'user has been successfully suppressed.');
            }
            return  redirect()->route("task.index")->with('status', '************ YOU DO NOT HAVE ACCESS TO DELETE user ! *************');

  
    }
}