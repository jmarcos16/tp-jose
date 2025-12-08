<?php

use App\Models\Project;
use App\Models\Task;

describe('ProjectController', function () {
    describe('GET /api/projects', function () {
        test('returns empty array when no projects exist', function () {
            $response = $this->getJson('/api/projects');

            $response->assertStatus(200)
                ->assertJson([]);
        });

        test('returns all projects', function () {
            Project::factory(3)->create();

            $response = $this->getJson('/api/projects');

            $response->assertStatus(200)
                ->assertJsonCount(3);
        });
    });

    describe('POST /api/projects', function () {
        test('creates a new project', function () {
            $response = $this->postJson('/api/projects', [
                'name' => 'New Project',
            ]);

            $response->assertStatus(201)
                ->assertJsonFragment(['name' => 'New Project']);

            $this->assertDatabaseHas('projects', ['name' => 'New Project']);
        });

        test('validates name is required', function () {
            $response = $this->postJson('/api/projects', []);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
        });

        test('validates name max length', function () {
            $response = $this->postJson('/api/projects', [
                'name' => str_repeat('a', 256),
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
        });
    });

    describe('GET /api/projects/{project}', function () {
        test('returns project with tasks', function () {
            $project = Project::factory()
                ->has(Task::factory(3))
                ->create();

            $response = $this->getJson("/api/projects/{$project->id}");

            $response->assertStatus(200)
                ->assertJsonFragment(['id' => $project->id])
                ->assertJsonCount(3, 'tasks');
        });

        test('returns 404 for non-existent project', function () {
            $response = $this->getJson('/api/projects/999');

            $response->assertStatus(404);
        });

        test('returns progress 0 when project has no tasks', function () {
            $project = Project::factory()->create();

            $response = $this->getJson("/api/projects/{$project->id}");

            $response->assertStatus(200)
                ->assertJsonFragment(['progress' => 0]);
        });

        test('returns progress 0 when all tasks are pending', function () {
            $project = Project::factory()->create();
            Task::factory(3)->pending()->create(['project_id' => $project->id]);

            $response = $this->getJson("/api/projects/{$project->id}");

            $response->assertStatus(200)
                ->assertJsonFragment(['progress' => 0]);
        });

        test('returns progress 100 when all tasks are completed', function () {
            $project = Project::factory()->create();
            Task::factory(3)->completed()->create(['project_id' => $project->id]);

            $response = $this->getJson("/api/projects/{$project->id}");

            $response->assertStatus(200)
                ->assertJsonFragment(['progress' => 100]);
        });
    });
});
