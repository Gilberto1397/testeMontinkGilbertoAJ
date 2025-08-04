# Teste empresa Montink

# Aplica��o feita com Laravel

- **Vers�o do Laravel:** 8.x
- **Vers�es aceitas do PHP:** ^7.3|^8.0

## Configura��o do Projeto

### 1. Configura��o do arquivo .env

Copie o arquivo `.env.example` para `.env`:
```bash
  cp .env.example .env
```

Configure as seguintes vari�veis no arquivo `.env`:

```env
# Configura��es do banco de dados
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=portaMysql
DB_DATABASE=nomeDoBanco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 2. Instala��o das depend�ncias

Execute os seguintes comandos:

```bash
# Instalar depend�ncias do Composer
composer install

# Gerar a chave da aplica��o
php artisan key:generate
```

### 3. Configura��o do banco de dados

Execute as migrations para criar as tabelas:

```bash
  php artisan migrate
```

### 4. Iniciar o servidor

Para iniciar o servidor de desenvolvimento:

```bash
  php artisan serve
```

### 5. Acessar a aplica��o

A aplica��o estar� dispon�vel em:
```
http://localhost:8000
```

## Rotas Inicial

- `http://127.0.0.1:8000/novo-produto`
