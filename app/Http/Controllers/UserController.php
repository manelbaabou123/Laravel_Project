<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return view('user.index', ['users' => User::paginate(10)]);
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
            return view('auth.register', Auth()->user()->hasRoleAdmin());
        } catch (\Exception $ex) {
            Log::critical("create error user".$ex->getMessage());
            abort(500);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   try {
            $request->validate(['name' => 'required', 'email' => 'required', 'password' => 'required']);
            User::create($request->all());
            return redirect()->route('user.index')->with('status', 'USer has been created successfully.');
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
    public function edit(Request $request): View
    {   try {
        return view('user.edit', ['user' => $request->user(),]);
        } catch (\Exception $ex) {
            Log::critical("edit error user".$ex->getMessage());
            abort(500);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('user.index', Auth()->user()->hasRoleAdmin())->with('status', 'user-updated');
        } catch (\Exception $ex) {
            Log::critical("update error user".$ex->getMessage());
            abort(500);
        }
      

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);
    
            $user = $request->user();
    
            Auth::logout();
    
            $user->delete();
    
            $request->session()->invalidate();
            $request->session()->regenerateToken();
    
            return Redirect::to('user.index', Auth()->user()->hasRoleAdmin())->with('status', 'user-updated');
        } catch (\Exception $ex) {
            Log::critical("destroy error user".$ex->getMessage());
            abort(500);
        }
        
    }
}