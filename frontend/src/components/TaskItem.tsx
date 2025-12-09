'use client';

import { useState } from 'react';
import { Task } from '@/types';
import { taskService } from '@/lib/services';
import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';

interface TaskItemProps {
  task: Task;
  onToggle: (task: Task) => void;
  onDelete: (taskId: number) => void;
}

const difficultyConfig = {
  baixa: { label: 'Fácil', variant: 'easy' as const },
  media: { label: 'Médio', variant: 'medium' as const },
  alta: { label: 'Difícil', variant: 'hard' as const },
};

export function TaskItem({ task, onToggle, onDelete }: TaskItemProps) {
  const [loading, setLoading] = useState(false);

  const handleToggle = async () => {
    setLoading(true);
    try {
      const updatedTask = await taskService.toggle(task.id);
      onToggle(updatedTask);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async () => {
    setLoading(true);
    try {
      await taskService.delete(task.id);
      onDelete(task.id);
    } finally {
      setLoading(false);
    }
  };

  const config = difficultyConfig[task.difficulty];

  return (
    <div className="flex items-center gap-4 pt-4 first:pt-0">
      <Checkbox
        checked={task.completed}
        onChange={handleToggle}
        disabled={loading}
      />
      <div className="flex-grow">
        <p className={`text-sm font-medium ${task.completed ? 'text-gray-400 line-through' : 'text-white'}`}>
          {task.title}
        </p>
      </div>
      <Badge variant={config.variant}>{config.label}</Badge>
      <button
        onClick={handleDelete}
        disabled={loading}
        className="text-gray-400 hover:text-red-400 transition-colors text-sm"
      >
        Excluir
      </button>
    </div>
  );
}
