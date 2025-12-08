<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Display a listing of all projects.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $projects = Project::all();

        return response()->json($projects);
    }

    /**
     * Store a newly created project in storage.
     *
     * @param StoreProjectRequest $request
     * @return JsonResponse
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        return response()->json($project, 201);
    }

    /**
     * Display the specified project with calculated progress.
     *
     * @param Project $project
     * @return JsonResponse
     */
    public function show(Project $project): JsonResponse
    {
        $project->load('tasks');
        $project->progress = $this->calculateProgress($project);

        return response()->json($project);
    }

    /**
     * Calculate the weighted progress of a project based on task effort points.
     *
     * @param Project $project
     * @return float
     */
    private function calculateProgress(Project $project): float
    {
        if ($project->tasks->isEmpty()) {
            return 0;
        }

        $totalEffort = 0;
        $completedEffort = 0;

        foreach ($project->tasks as $task) {
            $effort = $task->difficulty->effortPoints();
            $totalEffort += $effort;

            if ($task->completed) {
                $completedEffort += $effort;
            }
        }

        if ($totalEffort === 0) {
            return 0;
        }

        return round(($completedEffort / $totalEffort) * 100, 2);
    }
}
