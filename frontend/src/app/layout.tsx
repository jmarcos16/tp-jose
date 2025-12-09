import type { Metadata } from "next";
import "./globals.css";

export const metadata: Metadata = {
  title: "ProjectFlow - Gerenciador de Projetos",
  description: "Gerencie seus projetos e tarefas com progresso ponderado",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="pt-BR">
      <body className="antialiased">
        {children}
      </body>
    </html>
  );
}
