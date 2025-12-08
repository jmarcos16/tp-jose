<?php

namespace App\Enums;

enum DifficultyEnum: string
{
    case EASY = 'baixa';
    case MEDIUM = 'media';
    case HARD = 'alta';

    public function effortPoints(): int
    {
        return match ($this) {
            self::EASY => 1,
            self::MEDIUM => 4,
            self::HARD => 12,
        };
    }
}
