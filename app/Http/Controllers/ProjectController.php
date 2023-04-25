<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

            if (Auth()->user()->hasRoleAdmin()) {
                return view('project.index', ['projects' => Project::paginate(10)]);
            } else {
                abort(403, 'You do not have access to view projects.');
            }
 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    
            if (Auth()->user()->hasRoleAdmin()) {
                return view('project.create');
            } else {
                abort(403, 'You do not have access to create projects.');
            }

        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            if (Auth()->user()->hasRoleAdmin()) {
                $request->validate(['name' => 'required', 'description' => 'required']);
                Project::create($request->all());
                return redirect()->route('project.index')->with('status', 'Project has been created successfully.');
            } else {
                abort(403, 'You do not have access to store projects.');
            }

        
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
            if (Auth()->user()->hasRoleAdmin()) {
                return view('project.update', ['project' =>  $project]);
            } else {
                abort(403, 'You do not have access to edit projects.');
            }
   
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
       
            //  dd($request->all());
            if (Auth()->user()->hasRoleAdmin()) {

                $request->validate(['name' => 'required', 'description' => 'required']);
            
                $project->update([
                'name' => $request->name,
                'description' => $request->description
                ]);
                
                return redirect()->route('project.index')->with('status', 'Project has been successfully modified.');
            } else {
                abort(403, 'You do not have access to update projects.');
            }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
       
            if (Auth()->user()->hasRoleAdmin()) {
                $project->delete();
                return redirect()->route('project.index')->with('status', 'Project has been successfully suppressed.');
            } else {
                abort(403, 'You do not have access to destroy projects.');
            }
 
        
    }
}
