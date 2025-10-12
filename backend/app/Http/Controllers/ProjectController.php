<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Http\Requests\ProjectCreationRequest;
use App\Http\Requests\ProjectUpdateRequest;
use App\Models\Project;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\Middleware;

class ProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('roles:' . UserRoleEnum::ADMIN->value . ',' . UserRoleEnum::MANAGER->value, only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * project listing is cached for 60 minutes
     * Display a listing of the projects.
     */
    public function index()
    {
        $projects = Cache::remember('projects', 60, function () {
            return Project::all();
        });

        return response()->json(['projects' => $projects]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectCreationRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();
        $project = Project::create($validated);
        return response()->json(['project' => $project]);
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        return response()->json($project);
    }

    /**
     * Update the specified project in storage.
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        $project->update($request->validated());
        return response()->json($project);
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        // Check if the project has related tasks
        if ($project->tasks()->exists()) {
            return response()->json([
                'message' => __('messages.project_cannot_be_deleted')
            ], Response::HTTP_BAD_REQUEST);
        }

        $project->delete();
        return response()->json([
            'message' => __('messages.project_deleted')
        ], 200);
    }
}
