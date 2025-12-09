# Gerenciador de Projetos

Aplicação web SPA para gerenciar projetos e suas respectivas tarefas, com cálculo de progresso ponderado baseado na dificuldade de cada tarefa.

## Stack Tecnológica

### Backend
- **PHP 8.3** com **Laravel 12**
- **MySQL 8.4**
- **PestPHP** para testes

### Frontend
- **Next.js 16** com **React 19**
- **TypeScript**
- **Tailwind CSS 4**
- **Lucide React** para ícones

## Decisões Técnicas

### Cálculo de Progresso Ponderado

O progresso de um projeto é calculado considerando o esforço de cada tarefa:

| Dificuldade | Pontos de Esforço |
|-------------|-------------------|
| Baixa       | 1 ponto           |
| Média       | 4 pontos          |
| Alta        | 12 pontos         |

A proporção garante que uma tarefa média representa 1/3 do esforço de uma tarefa alta (4 = 12/3).

**Fórmula:**
```
Progresso = (Esforço Concluído / Esforço Total) × 100
```

### Arquitetura

- **Model Accessor**: O cálculo de progresso está implementado como um Accessor no Model `Project`, seguindo o princípio de responsabilidade única
- **Enum com comportamento**: `DifficultyEnum` encapsula os pontos de esforço via método `effortPoints()`
- **API RESTful**: Endpoints seguem as convenções REST

## Pré-requisitos

- Docker e Docker Compose instalados

## Como Executar

### 1. Clonar o repositório

```bash
git clone https://github.com/jmarcos16/tp-jose.git
cd tp-jose
```

### 2. Configurar variáveis de ambiente

```bash
cp backend/.env.example backend/.env
```

### 3. Subir os containers

```bash
docker compose up -d --build
```

Aguarde todos os containers estarem saudáveis:

```bash
docker compose ps
```

### 4. Gerar chave da aplicação

```bash
docker compose exec backend php artisan key:generate
```

### 5. Executar migrations

```bash
docker compose exec backend php artisan migrate
```

### 6. (Opcional) Popular com dados de teste

```bash
docker compose exec backend php artisan db:seed
```

### 6. Acessar a aplicação

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000/api

## API Endpoints

| Método | Endpoint                  | Descrição                          |
|--------|---------------------------|------------------------------------|
| GET    | `/api/projects`           | Listar todos os projetos           |
| POST   | `/api/projects`           | Criar um novo projeto              |
| GET    | `/api/projects/{id}`      | Obter projeto com tarefas          |
| POST   | `/api/tasks`              | Criar uma nova tarefa              |
| PATCH  | `/api/tasks/{id}/toggle`  | Alternar status da tarefa          |
| DELETE | `/api/tasks/{id}`         | Excluir uma tarefa                 |

## Testes

### Executar todos os testes

```bash
docker compose exec backend php artisan test
```

### Executar com cobertura

```bash
docker compose exec backend php artisan test --coverage
```

**Cobertura atual: 83.9%** com 44 testes e 81 assertions.

## Estrutura do Projeto

```
tp-jose/
├── docker-compose.yml
├── backend/
│   ├── app/
│   │   ├── Enums/
│   │   │   └── DifficultyEnum.php
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   ├── ProjectController.php
│   │   │   │   └── TaskController.php
│   │   │   └── Requests/
│   │   └── Models/
│   │       ├── Project.php
│   │       └── Task.php
│   └── tests/
│       ├── Unit/
│       │   └── ProjectProgressCalculationTest.php
│       └── Feature/
│           ├── ProjectControllerTest.php
│           ├── ProjectProgressTest.php
│           └── TaskControllerTest.php
└── frontend/
    └── src/
        ├── app/
        ├── components/
        ├── lib/
        └── types/
```

## Comandos Úteis

```bash
# Parar containers
docker compose down

# Ver logs
docker compose logs -f

# Acessar container do backend
docker compose exec backend bash

# Limpar e reconstruir
docker compose down -v && docker compose up -d --build
```
