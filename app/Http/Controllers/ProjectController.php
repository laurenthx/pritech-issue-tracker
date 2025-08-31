<?php

namespace App\Http\Controllers;

use App\Models\Project; // Import the Project model
use App\Models\Issue; // Needed for eager loading issues on show
use App\Http\Requests\StoreProjectRequest; // Import the custom form requests
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Facades\Auth; // <--- ADDED: To get the authenticated user's ID
use Illuminate\Http\Request; // Generally good practice to include

class ProjectController extends Controller
{
    public function __construct()
    {
        // This middleware ensures that all project actions require authentication.
        // `except` can be used to allow specific methods without authentication,
        // but for this task, all should be protected.
        $this->middleware('auth');

        // <--- ADDED: Authorize resource methods using ProjectPolicy
        $this->authorizeResource(Project::class, 'project');
        // This automatically applies viewAny for index, view for show, create for create/store,
        // update for edit/update, and delete for destroy.
        // You might still add explicit authorize calls if you need custom messages or different logic
        // in a specific method, or if `authorizeResource` isn't fitting perfectly.
        // For 'create', it calls the 'create' method on the policy, which is good.
    }

    /**
     * Display a listing of the resource (all projects).
     */
    public function index()
    {
        // The policy `viewAny` has already run due to __construct's authorizeResource.
        // Eager load the user (owner) to potentially display in the list.
        $projects = Project::with('user')->orderBy('created_at', 'desc')->paginate(10); // <--- ADDED pagination for requirement
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource (project).
     */
    public function create()
    {
        // The policy `create` has already run.
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        // The policy `create` has already run.
        $data = $request->validated();
        $data['user_id'] = Auth::id(); // <--- ADDED: Assign current authenticated user as owner
        $project = Project::create($data);

        return redirect()->route('projects.show', $project)->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified resource (a single project's details).
     */
    public function show(Project $project)
    {
        // The policy `view` has already run.
        // Eager load issues and the project owner for the detail page
        $project->load(['issues.tags', 'user']);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource (project).
     */
    public function edit(Project $project)
    {
        // The policy `update` has already run for this method.
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        // The policy `update` has already run for this method.
        $project->update($request->validated());
        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        // The policy `delete` has already run for this method.
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully!');
    }
}