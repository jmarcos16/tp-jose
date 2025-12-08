<?php

use App\Enums\DifficultyEnum;
use App\Models\Project;
use App\Models\Task;

describe('Project Progress Calculation', function () {
    test('progress is 0 when project has no tasks', function () {
        $project = Project::factory()->create();

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertJsonFragment(['progress' => 0]);
    });

    test('progress is 0 when all tasks are pending', function () {
        $project = Project::factory()->create();

        Task::factory()->easy()->pending()->create(['project_id' => $project->id]);
        Task::factory()->medium()->pending()->create(['project_id' => $project->id]);
        Task::factory()->hard()->pending()->create(['project_id' => $project->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertJsonFragment(['progress' => 0]);
    });

    test('progress is 100 when all tasks are completed', function () {
        $project = Project::factory()->create();

        Task::factory()->easy()->completed()->create(['project_id' => $project->id]);
        Task::factory()->medium()->completed()->create(['project_id' => $project->id]);
        Task::factory()->hard()->completed()->create(['project_id' => $project->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertJsonFragment(['progress' => 100]);
    });

    test('weighted progress with only easy task completed', function () {
        $project = Project::factory()->create();

        Task::factory()->easy()->completed()->create(['project_id' => $project->id]);
        Task::factory()->medium()->pending()->create(['project_id' => $project->id]);
        Task::factory()->hard()->pending()->create(['project_id' => $project->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertJsonFragment(['progress' => 5.88]);
    });

    test('weighted progress with only medium task completed', function () {
        $project = Project::factory()->create();

        Task::factory()->easy()->pending()->create(['project_id' => $project->id]);
        Task::factory()->medium()->completed()->create(['project_id' => $project->id]);
        Task::factory()->hard()->pending()->create(['project_id' => $project->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertJsonFragment(['progress' => 23.53]);
    });

    test('weighted progress with only hard task completed', function () {
        $project = Project::factory()->create();

        Task::factory()->easy()->pending()->create(['project_id' => $project->id]);
        Task::factory()->medium()->pending()->create(['project_id' => $project->id]);
        Task::factory()->hard()->completed()->create(['project_id' => $project->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertJsonFragment(['progress' => 70.59]);
    });

    test('weighted progress with easy and medium tasks completed', function () {
        $project = Project::factory()->create();

        Task::factory()->easy()->completed()->create(['project_id' => $project->id]);
        Task::factory()->medium()->completed()->create(['project_id' => $project->id]);
        Task::factory()->hard()->pending()->create(['project_id' => $project->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertJsonFragment(['progress' => 29.41]);
    });

    test('weighted progress with medium and hard tasks completed', function () {
        $project = Project::factory()->create();

        Task::factory()->easy()->pending()->create(['project_id' => $project->id]);
        Task::factory()->medium()->completed()->create(['project_id' => $project->id]);
        Task::factory()->hard()->completed()->create(['project_id' => $project->id]);

        $response = $this->getJson("/api/projects/{$project->id}");

        $response->assertJsonFragment(['progress' => 94.12]);
    });

    test('effort points are correct for each difficulty', function () {
        expect(DifficultyEnum::EASY->effortPoints())->toBe(1);
        expect(DifficultyEnum::MEDIUM->effortPoints())->toBe(4);
        expect(DifficultyEnum::HARD->effortPoints())->toBe(12);
    });

    test('medium difficulty is 1/3 of hard difficulty effort', function () {
        $mediumEffort = DifficultyEnum::MEDIUM->effortPoints();
        $hardEffort = DifficultyEnum::HARD->effortPoints();

        expect($mediumEffort)->toBe($hardEffort / 3);
    });
});
