<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = auth()->user()->projects()->get();
        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store()
    {
        //validate
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required|max:100',
            'notes' => 'min:3'
        ]);

        #$attributes['owner_id'] = auth()->id();

        //persist
        #Project::create($attributes);
        $project = auth()->user()->projects()->create($attributes);

        //redirect
        return redirect($project->path());
    }

    public function update(Project $project, Request $request)
    {
        $this->authorize('update', $project);
        #if(auth()->user()->isNot($project->owner)){
        #    abort(403);
        #}

        $project->update([
            'notes' => $request->notes
        ]);

        return redirect($project->path());
    }


}
