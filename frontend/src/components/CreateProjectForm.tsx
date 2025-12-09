'use client';

import { useState } from 'react';
import { Project } from '@/types';
import { projectService } from '@/lib/services';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

interface CreateProjectFormProps {
  onSuccess: (project: Project) => void;
}

export function CreateProjectForm({ onSuccess }: CreateProjectFormProps) {
  const [name, setName] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!name.trim()) return;

    setLoading(true);
    setError('');

    try {
      const project = await projectService.create({ name: name.trim() });
      setName('');
      onSuccess(project);
    } catch {
      setError('Erro ao criar projeto');
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="flex flex-col gap-3">
      <Input
        placeholder="Nome do projeto"
        value={name}
        onChange={(e) => setName(e.target.value)}
        disabled={loading}
      />
      <Button type="submit" disabled={loading || !name.trim()}>
        {loading ? 'Criando...' : 'Criar Projeto'}
      </Button>
      {error && <span className="text-red-400 text-sm">{error}</span>}
    </form>
  );
}
