# 👥 API Employees
API de gerenciamento de colaboradores desenvolvida para o teste técnico da Convenia.

### ✅ Requisitos
- PHP 8.2
- Mysql 8.0
- Composer

### 🚀 Instalação
1. Clone o repositório: `git clone https://github.com/emanuellyv/api-employees-convenia.git`
2. Acesse a pasta do projeto: `cd api-employees-convenia`
3. Instale as dependências PHP: `composer install`
4. Copie o arquivo de ambiente: `cp .env.example .env`
5. Edite o arquivo `.env` com as credenciais do seu banco de dados MySQL:
6. Gere a chave da aplicação: `php artisan key:generate`
7. Gere o jwt secret: 
   - `php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"`
   - `php artisan jwt:secret`
8. Execute as migrations: `php artisan migrate`

---

### 📫 Testes utilizando Postman
Os testes foram realizados utilizando o Postman, você pode [baixar a collection do Postman clicando aqui](API%20Employees.postman_collection.json)
e visualizar a [documentação da API](API-Documentation.md) para testar os endpoints.
