export type Difficulty = 'baixa' | 'media' | 'alta';

export interface Task {
  id: number;
  project_id: number;
  title: string;
  completed: boolean;
  difficulty: Difficulty;
  created_at: string;
  updated_at: string;
}

export interface Project {
  id: number;
  name: string;
  progress?: number;
  tasks?: Task[];
  created_at: string;
  updated_at: string;
}

export interface CreateProjectData {
  name: string;
}

export interface CreateTaskData {
  project_id: number;
  title: string;
  difficulty: Difficulty;
}
