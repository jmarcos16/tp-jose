<?php

use App\Enums\DifficultyEnum;
use App\Models\Project;
use App\Models\Task;

describe('Project Progress Accessor', function () {
    it('returns 0 when project has no tasks', function () {
        $project = new Project(['name' => 'Test Project']);
        $project->setRelation('tasks', collect([]));

        expect($project->progress)->toBe(0.0);
    });

    it('returns 0 when all tasks are pending', function () {
        $project = new Project(['name' => 'Test Project']);

        $tasks = collect([
            createTaskInstance(DifficultyEnum::EASY, false),
            createTaskInstance(DifficultyEnum::MEDIUM, false),
            createTaskInstance(DifficultyEnum::HARD, false),
        ]);

        $project->setRelation('tasks', $tasks);

        expect($project->progress)->toBe(0.0);
    });

    it('returns 100 when all tasks are completed', function () {
        $project = new Project(['name' => 'Test Project']);

        $tasks = collect([
            createTaskInstance(DifficultyEnum::EASY, true),
            createTaskInstance(DifficultyEnum::MEDIUM, true),
            createTaskInstance(DifficultyEnum::HARD, true),
        ]);

        $project->setRelation('tasks', $tasks);

        expect($project->progress)->toBe(100.0);
    });

    it('calculates weighted progress correctly with only easy task completed', function () {
        $project = new Project(['name' => 'Test Project']);

        $tasks = collect([
            createTaskInstance(DifficultyEnum::EASY, true),
            createTaskInstance(DifficultyEnum::MEDIUM, false),
            createTaskInstance(DifficultyEnum::HARD, false),
        ]);

        $project->setRelation('tasks', $tasks);

        expect($project->progress)->toBe(5.88);
    });

    it('calculates weighted progress correctly with only medium task completed', function () {
        $project = new Project(['name' => 'Test Project']);

        $tasks = collect([
            createTaskInstance(DifficultyEnum::EASY, false),
            createTaskInstance(DifficultyEnum::MEDIUM, true),
            createTaskInstance(DifficultyEnum::HARD, false),
        ]);

        $project->setRelation('tasks', $tasks);

        expect($project->progress)->toBe(23.53);
    });

    it('calculates weighted progress correctly with only hard task completed', function () {
        $project = new Project(['name' => 'Test Project']);

        $tasks = collect([
            createTaskInstance(DifficultyEnum::EASY, false),
            createTaskInstance(DifficultyEnum::MEDIUM, false),
            createTaskInstance(DifficultyEnum::HARD, true),
        ]);

        $project->setRelation('tasks', $tasks);

        expect($project->progress)->toBe(70.59);
    });

    it('calculates weighted progress with easy and medium completed', function () {
        $project = new Project(['name' => 'Test Project']);

        $tasks = collect([
            createTaskInstance(DifficultyEnum::EASY, true),
            createTaskInstance(DifficultyEnum::MEDIUM, true),
            createTaskInstance(DifficultyEnum::HARD, false),
        ]);

        $project->setRelation('tasks', $tasks);

        expect($project->progress)->toBe(29.41);
    });

    it('calculates weighted progress with medium and hard completed', function () {
        $project = new Project(['name' => 'Test Project']);

        $tasks = collect([
            createTaskInstance(DifficultyEnum::EASY, false),
            createTaskInstance(DifficultyEnum::MEDIUM, true),
            createTaskInstance(DifficultyEnum::HARD, true),
        ]);

        $project->setRelation('tasks', $tasks);

        expect($project->progress)->toBe(94.12);
    });

    it('proves medium difficulty is 1/3 of hard difficulty in weight calculation', function () {
        $projectWithMedium = new Project(['name' => 'Medium Project']);
        $projectWithMedium->setRelation('tasks', collect([
            createTaskInstance(DifficultyEnum::MEDIUM, true),
        ]));

        $projectWithHard = new Project(['name' => 'Hard Project']);
        $projectWithHard->setRelation('tasks', collect([
            createTaskInstance(DifficultyEnum::HARD, true),
        ]));

        expect($projectWithMedium->progress)->toBe(100.0);
        expect($projectWithHard->progress)->toBe(100.0);

        expect(DifficultyEnum::MEDIUM->effortPoints())->toBe(4);
        expect(DifficultyEnum::HARD->effortPoints())->toBe(12);
        expect(DifficultyEnum::MEDIUM->effortPoints())->toBe(DifficultyEnum::HARD->effortPoints() / 3);
    });

    it('handles multiple tasks of the same difficulty', function () {
        $project = new Project(['name' => 'Test Project']);

        $tasks = collect([
            createTaskInstance(DifficultyEnum::MEDIUM, true),
            createTaskInstance(DifficultyEnum::MEDIUM, true),
            createTaskInstance(DifficultyEnum::MEDIUM, false),
            createTaskInstance(DifficultyEnum::MEDIUM, false),
        ]);

        $project->setRelation('tasks', $tasks);

        expect($project->progress)->toBe(50.0);
    });

    it('handles single easy task completed', function () {
        $project = new Project(['name' => 'Test Project']);

        $tasks = collect([
            createTaskInstance(DifficultyEnum::EASY, true),
        ]);

        $project->setRelation('tasks', $tasks);

        expect($project->progress)->toBe(100.0);
    });
});

function createTaskInstance(DifficultyEnum $difficulty, bool $completed): Task
{
    $task = new Task();
    $task->title = 'Test Task';
    $task->difficulty = $difficulty;
    $task->completed = $completed;

    return $task;
}
