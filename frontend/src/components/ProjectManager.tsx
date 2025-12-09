'use client';

import { useState, useEffect, useCallback } from 'react';
import { Project, Task } from '@/types';
import { projectService } from '@/lib/services';
import { CreateProjectForm } from './CreateProjectForm';
import { CreateTaskForm } from './CreateTaskForm';
import { ProjectCard } from './ProjectCard';
import { TaskItem } from './TaskItem';
import { Progress } from '@/components/ui/progress';

export function ProjectManager() {
  const [projects, setProjects] = useState<Project[]>([]);
  const [selectedProject, setSelectedProject] = useState<Project | null>(null);
  const [loading, setLoading] = useState(true);

  const loadProjects = useCallback(async () => {
    try {
      const data = await projectService.getAll();
      setProjects(data);
    } finally {
      setLoading(false);
    }
  }, []);

  const loadProjectDetails = useCallback(async (projectId: number) => {
    const project = await projectService.getById(projectId);
    setSelectedProject(project);
    setProjects((prev) =>
      prev.map((p) => (p.id === projectId ? { ...p, progress: project.progress } : p))
    );
  }, []);

  useEffect(() => {
    loadProjects();
  }, [loadProjects]);

  const handleProjectCreated = (project: Project) => {
    setProjects((prev) => [...prev, { ...project, progress: 0 }]);
  };

  const handleProjectSelect = (project: Project) => {
    loadProjectDetails(project.id);
  };

  const handleTaskCreated = () => {
    if (selectedProject) {
      loadProjectDetails(selectedProject.id);
    }
  };

  const handleTaskToggle = (updatedTask: Task) => {
    if (selectedProject) {
      setSelectedProject((prev) => {
        if (!prev) return null;
        return {
          ...prev,
          tasks: prev.tasks?.map((t) => (t.id === updatedTask.id ? updatedTask : t)),
        };
      });
      loadProjectDetails(selectedProject.id);
    }
  };

  const handleTaskDelete = (taskId: number) => {
    if (selectedProject) {
      setSelectedProject((prev) => {
        if (!prev) return null;
        return {
          ...prev,
          tasks: prev.tasks?.filter((t) => t.id !== taskId),
        };
      });
      loadProjectDetails(selectedProject.id);
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center flex-1">
        <span className="text-gray-400">Carregando...</span>
      </div>
    );
  }

  return (
    <>
      <section className="w-full max-w-sm flex-shrink-0 glassmorphism-border border-r p-6 flex flex-col gap-6 glassmorphism-bg shadow-glass">
        <CreateProjectForm onSuccess={handleProjectCreated} />
        
        <div className="flex flex-col gap-2 overflow-y-auto pr-2 -mr-2">
          {projects.length === 0 ? (
            <p className="text-gray-400 text-center py-8 text-sm">
              Nenhum projeto criado
            </p>
          ) : (
            projects.map((project) => (
              <ProjectCard
                key={project.id}
                project={project}
                onClick={() => handleProjectSelect(project)}
                selected={selectedProject?.id === project.id}
              />
            ))
          )}
        </div>
      </section>

      <section className="flex-1 p-6 lg:p-8 flex flex-col gap-6 overflow-y-auto">
        {selectedProject ? (
          <>
            <div className="flex flex-col">
              <div className="flex items-center justify-between mb-2">
                <h2 className="text-2xl font-bold text-white">{selectedProject.name}</h2>
                <span className="text-lg font-medium text-gray-300">
                  {selectedProject.progress?.toFixed(0) ?? 0}%
                </span>
              </div>
              <Progress value={selectedProject.progress ?? 0} />
              <p className="text-sm text-gray-400 mt-2">
                Tarefas associadas ao projeto selecionado.
              </p>
            </div>

            <CreateTaskForm projectId={selectedProject.id} onSuccess={handleTaskCreated} />

            <div className="glassmorphism-border rounded-xl p-4 flex flex-col divide-y divide-white/10 glassmorphism-bg shadow-glass">
              {selectedProject.tasks?.length === 0 ? (
                <p className="text-gray-400 text-center py-8 text-sm">
                  Nenhuma tarefa criada
                </p>
              ) : (
                selectedProject.tasks?.map((task) => (
                  <TaskItem
                    key={task.id}
                    task={task}
                    onToggle={handleTaskToggle}
                    onDelete={handleTaskDelete}
                  />
                ))
              )}
            </div>
          </>
        ) : (
          <div className="flex items-center justify-center flex-1">
            <span className="text-gray-400">
              Selecione um projeto para ver as tarefas
            </span>
          </div>
        )}
      </section>
    </>
  );
}
