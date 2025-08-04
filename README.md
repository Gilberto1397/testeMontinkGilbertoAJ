# Teste empresa Montink

# Aplicação feita com Laravel

- **Versão do Laravel:** 8.x
- **Versões aceitas do PHP:** ^7.3|^8.0

## Configuração do Projeto

### 1. Configuração do arquivo .env

Copie o arquivo `.env.example` para `.env`:
```bash
  cp .env.example .env
```

Configure as seguintes variáveis no arquivo `.env`:

```env
# Configurações do banco de dados
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=portaMysql
DB_DATABASE=nomeDoBanco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 2. Instalação das dependências

Execute os seguintes comandos:

```bash
# Instalar dependências do Composer
composer install

# Gerar a chave da aplicação
php artisan key:generate
```

### 3. Configuração do banco de dados

Execute as migrations para criar as tabelas:

```bash
  php artisan migrate
```

### 4. Iniciar o servidor

Para iniciar o servidor de desenvolvimento:

```bash
  php artisan serve
```

### 5. Acessar a aplicação

A aplicação estará disponível em:
```
http://localhost:8000
```

## Rotas Inicial

- `http://127.0.0.1:8000/novo-produto`
