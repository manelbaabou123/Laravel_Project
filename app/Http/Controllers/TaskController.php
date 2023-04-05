<?php

namespace App\Http\Controllers;

use App\Models\task;
use Illuminate\Http\Request;
use App\Models\Project;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('task.index', ['tasks' => Task::paginate(4)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('task.create',['projects' => Project::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'description' => 'required', 'project_id' => 'required']);

        Task::create($request->all());

        return redirect()->route('task.index')->with('status', 'Task has been created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('task.update', ['task' =>  $task ,'projects' => Project::all()]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, task $task)
    {
        $request->validate(['name' => 'required', 'description' => 'required', 'project_id' => 'required']);
      
         $task->update([
            'name' => $request->name,
            'description' => $request->description,
            'project_id' => $request->project_id
        ]);
        
    

       return redirect()->route('task.index')->with('status', 'Task has been successfully modified.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('task.index')->with('status', 'Task has been successfully suppressed.');
    }
}
