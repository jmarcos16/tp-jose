import { api } from './api';
import { Project, CreateProjectData, Task, CreateTaskData } from '@/types';

export const projectService = {
  getAll: () => api.get<Project[]>('/projects'),
  getById: (id: number) => api.get<Project>(`/projects/${id}`),
  create: (data: CreateProjectData) => api.post<Project>('/projects', data),
};

export const taskService = {
  create: (data: CreateTaskData) => api.post<Task>('/tasks', data),
  toggle: (id: number) => api.patch<Task>(`/tasks/${id}/toggle`),
  delete: (id: number) => api.delete<void>(`/tasks/${id}`),
};
