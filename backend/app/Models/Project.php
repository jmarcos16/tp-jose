<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * @var array<int, string>
     */
    protected $appends = [
        'progress',
    ];

    /**
     * Get the tasks for the project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Calculate the weighted progress based on task effort points.
     *
     * Business rule: Progress is calculated by the ratio of completed effort
     * to total effort, where effort points are:
     * - Baixa (easy): 1 point
     * - Media (medium): 4 points
     * - Alta (hard): 12 points
     *
     * This ensures that medium = 1/3 of hard (4 = 12/3)
     */
    protected function progress(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                $tasks = $this->relationLoaded('tasks')
                    ? $this->tasks
                    : $this->tasks()->get();

                if ($tasks->isEmpty()) {
                    return 0;
                }

                $totalEffort = 0;
                $completedEffort = 0;

                foreach ($tasks as $task) {
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
        );
    }
}
