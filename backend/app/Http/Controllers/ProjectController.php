<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Display a listing of all projects.
     */
    public function index(): JsonResponse
    {
        $projects = Project::query()->with('tasks')->get();

        return response()->json($projects);
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = Project::query()->create($request->validated());

        return response()->json($project, 201);
    }

    /**
     * Display the specified project with tasks.
     */
    public function show(Project $project): JsonResponse
    {
        $project->load('tasks');

        return response()->json($project);
    }
}
