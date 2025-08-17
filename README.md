# AI-QFome

Eu desenvolvi esta API em Laravel 12 para gerenciar clientes e seus favoritos, integrando com um catálogo de produtos externo (FakeStore API). O ambiente roda em Docker com Laravel Octane (Swoole), Nginx, PostgreSQL e Redis.

URL padrão: http://localhost:8030

------------------------------------------------------------

## 1. Descrição técnica

- Framework: Laravel 12 (PHP 8.3)
- Runtime: Laravel Octane (Swoole)
- Proxy: Nginx
- Banco: PostgreSQL 16
- Cache/Queue/Sessão: Redis
- Testes: PHPUnit (Feature + Unit)
- Coleção Postman: `docs/postman/ai-qfome-api.postman_collection.json`
  - Importe a coleção no Postman/Insomnia e defina as variáveis de ambiente:
    - `base_url = http://localhost:8030`
    - `bearer_token = <token obtido no login>`

Estrutura de diretórios:
- `app/`
  - `Http/Controllers/API/*`: controladores REST
  - `Http/Requests/*`: validações e autenticação
  - `Http/Resources/*`: DTO/serialização
  - `Services/*`: regras de negócio e integrações (ex.: `Products/ProductService` consome FakeStore)
  - `Models/*`: modelos Eloquent
  - `Helpers/CurlHelper.php`: cliente HTTP enxuto usado pelo `ProductService`
- `database/migrations/`: schema do banco (inclui SoftDeletes)
- `tests/`: testes unitários e de feature
- `nginx/`, `Dockerfile`, `docker-compose.yaml`: infraestrutura

Principais endpoints expostos em `routes/api.php`:
- `POST /api/v1/users/authenticate` (login, gera token Sanctum)
- `GET /api/v1/products` e `GET /api/v1/products/{id}` (catálogo externo via cache)
- `apiResource customers` (CRUD)
- `apiResource customer-favorites` (CRUD)

Observações técnicas:
- ProductService aplica caching (Cache::remember) e valida HTTP code via `CurlHelper::info()['http_code']`.
- Campos de audit/soft delete presentes nos modelos que requerem (`SoftDeletes`).
- Autenticação de login valida credenciais manualmente (`Hash::check`), define usuário via `Auth::setUser()` e gera token Sanctum no serviço de autenticação.

------------------------------------------------------------

## 2. Modelagem de dados (ERD)

Diagrama lógico (simplificado):

```
+------------------+        +------------------------+
| users            |        | customers              |
|------------------|        |------------------------|
| id (PK)          |        | id (PK)                |
| name             |        | name                   |
| email (unique)   |        | email (unique?)        |
| password (hash)  |        | created_at             |
| deleted_at (SD)  |        | updated_at             |
| created_at       |        | deleted_at (SD)        |
| updated_at       |        +------------------------+
+------------------+                     |
                                         | 1..* (favorita)
                                         v
                              +---------------------------+
                              | customer_favorites        |
                              |---------------------------|
                              | id (PK)                   |
                              | customer_id (FK->customers.id)
                              | product_id (int, ref externo)
                              | created_at                |
                              | updated_at                |
                              | deleted_at (SD)           |
                              +---------------------------+

                [FakeStore API]
                Products (somente leitura, externo)
```

Notas:
- Produtos não são persistidos localmente; somente referenciados por `product_id` em `customer_favorites`.
- Chaves estrangeiras e índices definidos nas migrations conforme o caso.

------------------------------------------------------------

## 3. Arquitetura da aplicação

Visão macro (runtime):

```
[Nginx :80] -> proxy -> [Octane (Swoole) :8000 - Laravel]
                                   |
                                   +--> PostgreSQL (dados)
                                   |
                                   +--> Redis (cache/queue/sessões)
                                   |
                                   +--> FakeStore API (HTTP externo via CurlHelper)
```

Camadas internas:
- Controller: orquestra a requisição/response
- Request: valida entrada e autentica login
- Service: encapsula regras e integrações (ex.: ProductService)
- Resource: serializa saída para contratos estáveis
- Model/ORM: Eloquent + Scopes

------------------------------------------------------------

## 4. Como subir o projeto (Docker)

### 4.1. Pré-requisitos
- Docker Engine + Docker Compose
  - Windows: Docker Desktop
  - Linux/macOS: docker + docker compose plugin

### 4.2. Preparação inicial
1) Clone o projeto e entre no diretório raiz.

2) Variáveis de ambiente (.env)
   - Copie `.env.example` para `.env` e ajuste se necessário:
   ```bash
   # PowerShell
   if (-not (Test-Path .env) -and (Test-Path .env.example)) { Copy-Item .env.example .env }
   # Bash
   [ ! -f .env ] && [ -f .env.example ] && cp .env.example .env
   ```

3) Rede Docker
   - O `docker-compose.yaml` cria a rede `ai-qfome` automaticamente; Caso prefira criar manualmente, desabilite o `external: true` no compose e siga os passos abaixo para criar a rede manualmente.
   ```bash
   # PowerShell
   if (-not (docker network ls --format '{{.Name}}' | Select-String -SimpleMatch '^ai-qfome$')) { docker network create ai-qfome }
   # Bash
   docker network ls --format '{{.Name}}' | grep -qx 'ai-qfome' || docker network create ai-qfome
   ```

4) Usuário semeado (seed)
   - O `docker-compose.yaml` executa `php artisan db:seed` automaticamente na subida do container `app`.
   - As credenciais padrão são definidas por variáveis de ambiente (você pode personalizar no compose ou no `.env`):
   ```env
   SEED_USER_EMAIL=admin@mail.com
   SEED_USER_NAME=Admin
   SEED_USER_PASSWORD=supersecret
   ```
   - Essas credenciais serão usadas no fluxo de autenticação descrito na seção 6.1.
   - Banco (valores padrão do compose):
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=postgres
   DB_PORT=5432
   DB_DATABASE=ai-qfome_db
   DB_USERNAME=ai-qfome_user
   DB_PASSWORD=ai-qfome_pass
   ```

### 4.3. Build e subida
```bash
docker compose up -d --build
```

Aplicação disponível em: http://localhost:8030

O container `app` executa automaticamente:
- `php artisan config:cache`
- `php artisan migrate --force`
- inicialização do Laravel Octane (Swoole) em :8000 (Nginx faz proxy)

### 4.4. Comandos úteis
- Logs
```bash
docker compose logs -f nginx
docker compose logs -f app
docker compose logs -f postgres
docker compose logs -f redis
```

- Artisan dentro do container `app`
```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan tinker
docker compose exec app php artisan test
```

- Dependências dentro do container
```bash
docker compose exec app composer install
docker compose exec app composer update
```

- Subida/Parada
```bash
docker compose up -d
docker compose down         # mantém volumes
docker compose down -v      # remove volumes (apaga dados)
```

------------------------------------------------------------

## 5. Execução de testes

Localmente (fora ou dentro do container `app`):
```bash
php artisan test
# ou
docker compose exec app php artisan test
```

Os testes cobrem:
- Services (ex.: `Products/ProductService`) com mocks de Cache/HTTP
- Controllers de Feature (Customers, Customer Favorites, Products)
- Autenticação (login) com validação 200/422

##### Nota: Caso execute os testes no docker, será necessário executar as migrations novamente após a finalização dos testes.

------------------------------------------------------------

## 6. Endpoints principais

Autenticação
- `POST /api/v1/users/authenticate`
  - body: `{ email, password }`
  - 200: `{ token, user }`
  - 422: validação inválida

### 6.1. Autenticação: passo a passo

O fluxo de login é simples e direto. As credenciais de acesso são geradas via seeder assim que a aplicação sobe. Elas são configuráveis por variáveis de ambiente no `docker-compose.yaml` ou no arquivo `.env`:

```env
SEED_USER_EMAIL=admin@mail.com
SEED_USER_NAME=Admin
SEED_USER_PASSWORD=supersecret
```
Para obter o token Bearer, siga os passos:

1) Requisição de login
   - Endpoint: `POST /api/v1/users/authenticate`
   - Body (JSON) — use o usuário semeado por padrão:
   ```json
   {
     "email": "admin@mail.com",
     "password": "supersecret"
   }
   ```
   - Resposta (200):
   ```json
   {
     "token": "<bearer_token>",
     "user": {
       "id": 1,
       "name": "Admin",
       "email": "admin@mail.com"
     }
   }
   ```

2) Uso do token nas próximas requisições
   - Envie o cabeçalho `Authorization: Bearer <bearer_token>` em rotas protegidas (`/api/v1/customers`, `/api/v1/customer-favorites`, etc.).

3) Dica rápida (cURL)
```bash
curl -s -X POST http://localhost:8030/api/v1/users/authenticate \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@mail.com","password":"supersecret"}'
```
Com o token em mãos:
```bash
curl -s http://localhost:8030/api/v1/customers \
  -H "Authorization: Bearer <bearer_token>"
```

Produtos (somente leitura)
- `GET /api/v1/products`
- `GET /api/v1/products/{id}`

Clientes
- `GET/POST/PUT/DELETE /api/v1/customers`

Favoritos do cliente
- `GET/POST/PUT/DELETE /api/v1/customer-favorites`

Obs.: rotas (exceto authenticate) protegidas por `auth:sanctum` no grupo.

------------------------------------------------------------

## 7. Troubleshooting

- Porta 8030 em uso: ajuste mapeamento do `nginx` no `docker-compose.yaml`.
- Rede externa `ai-qfome` não encontrada: crie a rede ou remova `external: true` no compose.
- Permissões em Linux:
```bash
sudo chown -R $USER:$USER storage bootstrap/cache
sudo chmod -R ug+rw storage bootstrap/cache
```
- Erro `Could not open input file: artisan` fora do Docker: verifique existência do arquivo `artisan` na raiz.

- Token inválido após recriar containers/volumes: gere um novo token refazendo o login ou limpe caches com `docker compose exec app php artisan optimize:clear`.

------------------------------------------------------------

## 8. Roadmap técnico (sugestões e melhorias desejadas)

- Observabilidade (metrics/tracing) integrar com Datadog / Telescope.
- Circuit breaker para o catálogo: Proteção de falhas e otimização de performance, caso a api externa venha a falhar, a aplicação não quebre, e continue operacional (fallback de dados em cache).
- Cache warming para o catálogo: popular cache antes de o usuário precisar (job / command).
- Implementar Kafka para envio de logs e metrics para Datadog, e chamadas de serviços externos.  