<?php

namespace App\Http\Controllers;

use App\Models\project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('project.index', ['projects' => Project::paginate(4)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'description' => 'required']);

        Project::create($request->all());

        return redirect()->route('project.index')->with('status', 'Project has been created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return view('project.update', ['project' =>  $project]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, project $project)
    {

      //  dd($request->all());
        $request->validate(['name' => 'required', 'description' => 'required']);
      
         $project->update([
            'name' => $request->name,
            'description' => $request->description
        ]);
        
    

       return redirect()->route('project.index')->with('status', 'Project has been successfully modified.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('project.index')->with('status', 'Project has been successfully suppressed.');
    }
}
