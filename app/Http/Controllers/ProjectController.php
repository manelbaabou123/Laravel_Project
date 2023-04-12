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
        try {
            if(Auth()->user()->hasRoleAdmin())
            return view('project.index', ['projects' => Project::paginate(10)]);
            return  redirect()->route("task.index")->with('status', '************ YOU DO NOT HAVE ACCESS TO PROJECTS ! *************');

        } catch (Exception $ex) {
            Log::critical("index error project".$ex->getMessage());
            abort(500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            if(Auth()->user()->hasRoleAdmin())
            return view('project.create');
            return  redirect()->route("task.index")->with('status', '************ YOU DO NOT HAVE ACCESS TO CREATE PROJECT ! *************');

        } catch (Exception $ex) {
            Log::critical("create error project".$ex->getMessage());
            abort(500);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   try {
            $request->validate(['name' => 'required', 'description' => 'required']);
            Project::create($request->all());
            return redirect()->route('project.index')->with('status', 'Project has been created successfully.');
        } catch (Exception $ex) {
            Log::critical("store error project".$ex->getMessage());
            abort(500);
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
    {   try {
        if(Auth()->user()->hasRoleAdmin())
            return view('project.update', ['project' =>  $project]);
            return  redirect()->route("task.index")->with('status', '************ YOU DO NOT HAVE ACCESS TO UPDATE PROJECT ! *************');

        } catch (Exception $ex) {
            Log::critical("edit error project".$ex->getMessage());
            abort(500);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        try {
            //  dd($request->all());
            $request->validate(['name' => 'required', 'description' => 'required']);
        
            $project->update([
            'name' => $request->name,
            'description' => $request->description
            ]);
            
            return redirect()->route('project.index')->with('status', 'Project has been successfully modified.');
        } catch (Exception $ex) {
            Log::critical("update error project".$ex->getMessage());
            abort(500);
        }
      

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            if (Auth()->user()->hasRoleAdmin())
            {
            $project->delete();
            return redirect()->route('project.index')->with('status', 'Project has been successfully suppressed.');
            }
            return  redirect()->route("task.index")->with('status', '************ YOU DO NOT HAVE ACCESS TO DELETE PROJECT ! *************');

        } catch (Exception $ex) {
            Log::critical("destroy error project".$ex->getMessage());
            abort(500);
        }
        
    }
}
