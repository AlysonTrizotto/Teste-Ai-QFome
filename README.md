AI-QFome - Ambiente Docker (Laravel Octane + Swoole, Nginx, PostgreSQL, Redis)

## Visão Geral

Este projeto usa Docker para subir um ambiente Laravel 12 com Octane (Swoole) atrás de um Nginx, além de PostgreSQL e Redis.

- App: `php:8.3-cli-alpine` com PHP extensions (pdo_pgsql, redis, swoole) e Octane
- Proxy: Nginx (porta pública 8030)
- Banco: PostgreSQL 16 (porta 5432)
- Cache/Fila/Sessão: Redis (porta 6379)

Arquivos principais:
- `docker-compose.yaml`
- `Dockerfile`
- `nginx/conf.d/default.conf`

URL padrão: http://localhost:8030

## Pré-requisitos

- Docker Engine + Docker Compose
  - Windows: Docker Desktop
  - Linux: docker + docker compose plugin

## Preparação inicial

1) Clone/baixe o projeto e entre no diretório do projeto.

2) Variáveis de ambiente (.env)
   - Garanta que existe um `.env` válido (você pode copiar de `.env.example`):
     - Windows (PowerShell):
       ```powershell
       if (-not (Test-Path .env) -and (Test-Path .env.example)) { Copy-Item .env.example .env }
       ```
     - Linux/macOS (bash):
       ```bash
       [ ! -f .env ] && [ -f .env.example ] && cp .env.example .env
       ```
   - Ajuste as credenciais do banco p/ PostgreSQL se necessário:
     ```env
     DB_CONNECTION=pgsql
     DB_HOST=postgres
     DB_PORT=5432
     DB_DATABASE=ai-qfome_db
     DB_USERNAME=ai-qfome_user
     DB_PASSWORD=ai-qfome_pass
     ```

3) Rede Docker
   - O `docker-compose.yaml` usa uma rede externa chamada `ai-qfome`.
   - Você pode criar a rede manualmente ou remover o `external: true` do compose para o Docker criar automaticamente.
   - Criar rede manualmente:
     - Windows (PowerShell):
       ```powershell
       if (-not (docker network ls --format '{{.Name}}' | Select-String -SimpleMatch '^ai-qfome$')) { docker network create ai-qfome }
       ```
     - Linux/macOS (bash):
       ```bash
       docker network ls --format '{{.Name}}' | grep -qx 'ai-qfome' || docker network create ai-qfome
       ```

## Subir o ambiente

1) Build das imagens
   - Windows/Linux:
     ```bash
     docker compose build
     ```

2) Subir em modo daemon
   - Windows/Linux:
     ```bash
     docker compose up -d
     ```

3) Acessar a aplicação
   - Abra: http://localhost:8030

> Observação: o serviço `app` executa automaticamente `php artisan config:cache`, `php artisan migrate --force` e inicia o Octane Swoole na porta 8000. O Nginx faz proxy para essa porta.

## Comandos úteis

- Logs em tempo real
  ```bash
  docker compose logs -f nginx
  docker compose logs -f app
  docker compose logs -f postgres
  docker compose logs -f redis
  ```

- Executar comandos Artisan dentro do container `app`
  - Windows (PowerShell) e Linux/macOS (bash):
    ```bash
    docker compose exec app php artisan migrate
    docker compose exec app php artisan tinker
    ```

- Instalar/atualizar dependências dentro do container
  ```bash
  docker compose exec app composer install
  docker compose exec app composer update
  docker compose exec app npm run build
  docker compose exec app npm run dev
  ```

- Parar e remover containers (mantendo volumes de dados)
  ```bash
  docker compose down
  ```

- Parar e remover containers + volumes (cuidado: apaga dados do banco)
  ```bash
  docker compose down -v
  ```

## Dicas (Linux)

- Se encontrar problemas de permissão em `storage/` ou `bootstrap/cache/`, rode:
  ```bash
  sudo chown -R $USER:$USER storage bootstrap/cache
  sudo chmod -R ug+rw storage bootstrap/cache
  ```

## Estrutura dos serviços (compose)

- `app`: Laravel + Octane (Swoole), porta interna 8000
- `nginx`: proxy reverso para `app:8000`, expõe `8030:80`
- `postgres`: banco de dados PostgreSQL 16, expõe `5432:5432`
- `redis`: Redis (cache/fila/sessão), expõe `6379:6379`

## Troubleshooting

- Porta 8030 já em uso
  - Ajuste o mapeamento em `docker-compose.yaml` no serviço `nginx`.

- Erro de rede externa `ai-qfome` não encontrada
  - Crie a rede (ver seção de preparação) ou remova `external: true` do compose.

- `Could not open input file: artisan` ao rodar Composer fora do Docker
  - Certifique-se de que existe o arquivo `artisan` (sem extensão) no root do projeto.