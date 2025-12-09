import { ProjectManager } from '@/components/ProjectManager';

export default function Home() {
  return (
    <div className="relative min-h-screen w-full overflow-hidden">
      <div className="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#137fec]/20 rounded-full blur-[150px] pointer-events-none" />
      <div className="absolute bottom-0 right-0 translate-x-1/2 translate-y-1/2 w-[500px] h-[500px] bg-fuchsia-600/20 rounded-full blur-[150px] pointer-events-none" />
      
      <div className="flex flex-col min-h-screen w-full relative z-10">
        <header className="flex items-center justify-between px-6 py-4 border-b glassmorphism-border bg-black/20 backdrop-blur-sm">
          <div className="flex items-center gap-3">
            <div className="w-8 h-8 rounded-full bg-[#137fec] flex items-center justify-center text-white font-bold text-sm">
              P
            </div>
            <h1 className="text-white text-base font-medium">ProjectFlow</h1>
          </div>
        </header>
        
        <main className="flex-1 w-full flex overflow-hidden">
          <ProjectManager />
        </main>
      </div>
    </div>
  );
}
