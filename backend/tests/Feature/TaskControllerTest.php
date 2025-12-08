<?php

use App\Enums\DifficultyEnum;
use App\Models\Project;
use App\Models\Task;

describe('TaskController', function () {
    describe('POST /api/tasks', function () {
        test('creates a new task', function () {
            $project = Project::factory()->create();

            $response = $this->postJson('/api/tasks', [
                'project_id' => $project->id,
                'title' => 'New Task',
                'difficulty' => DifficultyEnum::MEDIUM->value,
            ]);

            $response->assertStatus(201)
                ->assertJsonFragment([
                    'title' => 'New Task',
                    'difficulty' => DifficultyEnum::MEDIUM->value,
                ]);

            $this->assertDatabaseHas('tasks', [
                'project_id' => $project->id,
                'title' => 'New Task',
                'difficulty' => DifficultyEnum::MEDIUM->value,
            ]);
        });

        test('validates project_id is required', function () {
            $response = $this->postJson('/api/tasks', [
                'title' => 'New Task',
                'difficulty' => DifficultyEnum::MEDIUM->value,
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['project_id']);
        });

        test('validates project_id exists', function () {
            $response = $this->postJson('/api/tasks', [
                'project_id' => 999,
                'title' => 'New Task',
                'difficulty' => DifficultyEnum::MEDIUM->value,
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['project_id']);
        });

        test('validates title is required', function () {
            $project = Project::factory()->create();

            $response = $this->postJson('/api/tasks', [
                'project_id' => $project->id,
                'difficulty' => DifficultyEnum::MEDIUM->value,
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
        });

        test('validates difficulty is required', function () {
            $project = Project::factory()->create();

            $response = $this->postJson('/api/tasks', [
                'project_id' => $project->id,
                'title' => 'New Task',
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['difficulty']);
        });

        test('validates difficulty must be valid enum value', function () {
            $project = Project::factory()->create();

            $response = $this->postJson('/api/tasks', [
                'project_id' => $project->id,
                'title' => 'New Task',
                'difficulty' => 'invalid',
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['difficulty']);
        });
    });

    describe('PATCH /api/tasks/{task}/toggle', function () {
        test('toggles task from pending to completed', function () {
            $task = Task::factory()->pending()->create();

            $response = $this->patchJson("/api/tasks/{$task->id}/toggle");

            $response->assertStatus(200)
                ->assertJsonFragment(['completed' => true]);

            $this->assertDatabaseHas('tasks', [
                'id' => $task->id,
                'completed' => true,
            ]);
        });

        test('toggles task from completed to pending', function () {
            $task = Task::factory()->completed()->create();

            $response = $this->patchJson("/api/tasks/{$task->id}/toggle");

            $response->assertStatus(200)
                ->assertJsonFragment(['completed' => false]);

            $this->assertDatabaseHas('tasks', [
                'id' => $task->id,
                'completed' => false,
            ]);
        });

        test('returns 404 for non-existent task', function () {
            $response = $this->patchJson('/api/tasks/999/toggle');

            $response->assertStatus(404);
        });
    });

    describe('DELETE /api/tasks/{task}', function () {
        test('deletes a task', function () {
            $task = Task::factory()->create();

            $response = $this->deleteJson("/api/tasks/{$task->id}");

            $response->assertStatus(204);

            $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        });

        test('returns 404 for non-existent task', function () {
            $response = $this->deleteJson('/api/tasks/999');

            $response->assertStatus(404);
        });
    });
});
