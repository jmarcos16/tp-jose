# Teste Prático – Desenvolvedor(a) Full Stack PHP

Olá candidato!

Agradecemos seu interesse em fazer parte do time da Plan Marketing.

Este teste prático foi desenhado para nos dar uma visão de como você estrutura seu código, modela soluções e resolve problemas lógicos. O objetivo não é criar um produto completo, mas sim avaliar a qualidade, a organização e a profundidade técnica do seu trabalho.

## O Desafio: Gerenciador de Projetos

Você deverá construir uma aplicação web single-page (SPA) para gerenciar projetos e suas respectivas tarefas. A aplicação será composta por uma API RESTful em Laravel e uma interface de usuário em um framework JavaScript moderno.

## Requisitos do Back-end (Laravel)

### 1. Estrutura e Banco de Dados
Utilize a versão estável mais recente do Laravel e MySQL.

### 2. Models e Relacionamentos
Você precisará de duas entidades principais:

- **Project**: com os campos `id` e `name`.
- **Task**: com os campos `id`, `title`, `completed` (boolean), `project_id`, e um novo campo `difficulty`.

O campo `difficulty` deve armazenar a dificuldade da tarefa (ex: baixa, média, alta).

Implemente o relacionamento Eloquent correto (Project tem muitas Tasks, Task pertence a um Project).

### 3. API Endpoints

- `GET /api/projects`: Listar todos os projetos.
- `POST /api/projects`: Criar um novo projeto.
- `POST /api/tasks`: Criar uma nova tarefa associada a um projeto (deve incluir o campo `difficulty`).
- `PATCH /api/tasks/:id/toggle`: Marcar uma tarefa como concluída ou não.
- `DELETE /api/tasks/:id`: Excluir uma tarefa.

### 4. ⭐ Desafio de Lógica Principal (Progresso Ponderado) ⭐

O progresso de um projeto deve ser calculado de forma ponderada pelo esforço de cada tarefa.

**Sistema de Pontos de Esforço**: A regra de negócio é: uma tarefa de dificuldade 'média' deve representar 1/3 do esforço de uma tarefa 'alta'. Para manter uma proporção lógica com a dificuldade 'baixa', utilize o seguinte sistema de pontos em seu cálculo:

- **Baixa**: 1 ponto de esforço.
- **Média**: 4 pontos de esforço.
- **Alta**: 12 pontos de esforço.

**Cálculo do Progresso**: O progresso do projeto será a porcentagem de tarefas concluídas em relação ao total de tarefas do projeto, considerando o esforço de cada tarefa nesse cálculo.

**Observação**: Se um projeto não tiver tarefas, seu progresso é 0%.

**Endpoint a ser implementado**:
- Crie ou ajuste o endpoint `GET /api/projects/:id`.
- Ele deve retornar os dados do projeto e o campo calculado `progress`. A implementação desta lógica é ponto de avaliação.

## Requisitos do Front-end

### 1. Estrutura
Utilize um framework JavaScript moderno de sua preferência (Vue.js, React.js ou Next.js).

### 2. Funcionalidades

- Visualizar uma lista de projetos (exibindo o progresso atual) e tarefas (projeto vinculado e dificuldade).
- Ao criar uma nova tarefa, o usuário deve definir sua dificuldade (baixa, média ou alta).
- Marcar tarefas como concluídas (o progresso do projeto deve ser recalculado e atualizado na interface).

## Requisitos Gerais e Entregáveis

1. **Repositório**: Projeto completo em um repositório público no seu GitHub.
2. **Histórico de Commits**: Commits claros e incrementais.
3. **Containerização**: Um arquivo `docker-compose.yml` para subir todo o ambiente.
4. **Testes (Diferencial)**: Testes unitários/integração para a API, especialmente para a lógica de cálculo do progresso.
5. **Documentação (README.md)**: Obrigatório. Deve conter:
   - Descrição do projeto e decisões técnicas.
   - Instruções claras para executar o projeto localmente.

## O que será Avaliado

- **Lógica de Negócio e Resolução de Problemas**: Sua abordagem para implementar a lógica na api, a modelagem dos dados e a eficiência da solução.
- **Qualidade e Clareza do Código**: Código limpo, legível e seguindo os padrões da indústria.
- **Estrutura do Projeto e separação de responsabilidades**.
- **Aplicação das Boas Práticas do Laravel e do Framework Front-end**.
- **Qualidade da Documentação e Histórico do Git**.

## Prazo e Envio

O prazo final para a entrega do teste é **15/12/2025**. Quando finalizar, por favor, envie o link do seu repositório no GitHub em resposta a este e-mail.

**Boa sorte e estamos ansiosos para ver sua solução!**