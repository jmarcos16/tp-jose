'use client';

import { useState } from 'react';
import { Task, Difficulty } from '@/types';
import { taskService } from '@/lib/services';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select } from '@/components/ui/select';

interface CreateTaskFormProps {
  projectId: number;
  onSuccess: (task: Task) => void;
}

export function CreateTaskForm({ projectId, onSuccess }: CreateTaskFormProps) {
  const [title, setTitle] = useState('');
  const [difficulty, setDifficulty] = useState<Difficulty>('media');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!title.trim()) return;

    setLoading(true);
    setError('');

    try {
      const task = await taskService.create({
        project_id: projectId,
        title: title.trim(),
        difficulty,
      });
      setTitle('');
      setDifficulty('media');
      onSuccess(task);
    } catch {
      setError('Erro ao criar tarefa');
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="flex gap-3 items-center">
      <Input
        placeholder="Nova tarefa..."
        value={title}
        onChange={(e) => setTitle(e.target.value)}
        disabled={loading}
        className="flex-1"
      />
      <Select
        value={difficulty}
        onChange={(e) => setDifficulty(e.target.value as Difficulty)}
        disabled={loading}
      >
        <option value="baixa">Fácil</option>
        <option value="media">Médio</option>
        <option value="alta">Difícil</option>
      </Select>
      <Button type="submit" disabled={loading || !title.trim()} size="sm">
        {loading ? '...' : 'Adicionar'}
      </Button>
      {error && <span className="text-red-400 text-sm">{error}</span>}
    </form>
  );
}
