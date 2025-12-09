'use client';

import { Project } from '@/types';
import { Progress } from '@/components/ui/progress';

interface ProjectCardProps {
  project: Project;
  onClick: () => void;
  selected?: boolean;
}

export function ProjectCard({ project, onClick, selected }: ProjectCardProps) {
  return (
    <div
      onClick={onClick}
      className={`glassmorphism-border rounded-lg p-4 cursor-pointer transition-all duration-200 ${
        selected
          ? 'glassmorphism-active shadow-lg'
          : 'glassmorphism-hover'
      }`}
    >
      <div className="flex justify-between items-center mb-2">
        <p className="text-sm font-medium text-white">{project.name}</p>
        <span className="text-sm text-gray-300">{project.progress?.toFixed(0) ?? 0}%</span>
      </div>
      <Progress value={project.progress ?? 0} />
    </div>
  );
}
