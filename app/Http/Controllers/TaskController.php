<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //$this->authorize('view', Task::class);
            if (Auth()->user()->hasRoleAdmin() || Auth()->user()) {

                $tasks =  Auth()->user()->hasRoleAdmin() ? Task::paginate(10) : Auth()->user()->tasks()->paginate(10);
                return view('task.index', ['tasks' => $tasks]);
                //return view('task.index',  ['tasks' => Task::paginate(10)]);
            } else {
                abort(403, 'You do not have access to index Tasks.');
            }
         } catch (\Exception $ex) {
             Log::critical("index error task".$ex->getMessage());
             abort(500);
         }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            if (Auth()->user()->hasRoleAdmin() || Auth()->user()) {
                return view('task.create', ['projects' => Project::all(), 'users' => User::all()]);
            } else {
                abort(403, 'You do not have access to create Tasks.');
            }
        } catch (\Exception $ex) {
            Log::critical("create error task".$ex->getMessage());
            abort(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if (Auth()->user()->hasRoleAdmin() || Auth()->user()) {

                $request->validate([
                    'name' => 'required',
                    'description' => 'required',
                    'project_id' => 'required',
                    'user_id' =>  Auth()->user()->hasRoleAdmin() ?  'required' : ''
                ]);

                Task::create( [
                    "project_id" =>  $request->project_id,
                    "name" =>  $request->name,
                    "description" => $request->description,
                    'user_id' => Auth()->user()->hasRoleAdmin() ?  $request->user_id  : Auth::id()
            ]);
        
            //$this->authorize('store', Task::class);
            return redirect()->route('task.index')->with('status', 'Task has been created successfully.');
            } else {
                abort(403, 'You do not have access to store Tasks.');
            }
        } catch (\Exception $ex) {
            Log::critical("store error task".$ex->getMessage());
            abort(500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        try {
            if (Auth()->user()->hasRoleAdmin() || Auth()->user()) {

                //$this->authorize('update', $task);
                return view('task.update', ['task' =>  $task ,'projects' => Project::all(), 'users' => User::all()]);
            } else {
                abort(403, 'You do not have access to edit Tasks.');
            }
        } catch (\Exception $ex) {
           Log::critical("edit error task".$ex->getMessage());
           abort(500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        // try {
            if (Auth()->user()->hasRoleAdmin() || Auth()->user()) {

            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'project_id' => 'required',
                //'user_id' => 'required',
                'user_id' =>  Auth()->user()->hasRoleAdmin() ?  'required' : ''

            ]);
      
            $task->update([
                'name' => $request->name,
                'description' => $request->description,
                'project_id' => $request->project_id,
                // 'user_id' => $request->user_id,
                'user_id' => Auth()->user()->hasRoleAdmin() ?  $request->user_id  : Auth::id()
            ]);
            
            return redirect()->route('task.index')->with('status', 'Task has been successfully modified.');
        } else {
            abort(403, 'You do not have access to update Tasks.');
        }
        // } catch (\Exception $ex) {
        //     Log::critical("update error task".$ex->getMessage());
        //     abort(500);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {

            $this->authorize('delete', $task);
    

            $task->delete();
            return redirect()->route('task.index')->with('status', 'Task has been successfully suppressed.');
       
        } catch (\Exception $ex) {
            Log::critical("destroy error task".$ex->getMessage());
            abort(500);
        }
    }
}
