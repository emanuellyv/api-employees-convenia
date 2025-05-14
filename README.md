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

5. Gere a chave da aplicação: `php artisan key:generate`

6. Edite o arquivo `.env` com as credenciais do seu banco de dados MySQL:

7. Execute as migrations: `php artisan migrate`

---

### 📫 Testes utilizando Postman
Você pode [baixar a collection do Postman clicando aqui](API%20Employees.postman_collection.json), seguir os passos abaixo para importar no Postman e testar os endpoints.

##### 📥 Importando a Collection
1. Abra o Postman.
2. Clique em "Import".
3. Vá até a aba "File" e selecione o arquivo API Employees.postman_collection.json.
4. Clique em "Import" para adicionar a collection.

> 💡 A variável {{urlLocal}} está definida como http://127.0.0.1:8000/. Certifique-se de que a aplicação esteja rodando nesse endereço (php artisan serve).

##### 📚 Endpoints e suas funções

👤 Gerenciar Gestores

* GET /api/managers/ — Listar todos os gestores
* GET /api/managers/{id} — Buscar gestor por ID
* POST /api/managers/ — Criar novo gestor
```json
{
    "name": "Gestor",
    "email": "gestor@teste.com"
}
```
* PUT /api/managers/{id} — Editar gestor
* DELETE /api/managers/{id} — Excluir gestor

👥 Gerenciar Colaboradores
* GET /api/employees/ — Listar todos os colaboradores
* GET /api/employees/102 — Buscar colaborador por ID
* POST /api/employees/ — Criar novo colaborador
```json
{
    "name": "Colaborador",
    "email": "colaborador@teste.com",
    "cpf": "123.456.789-10",
    "city": "São Paulo",
    "state": "São Paulo"
}
```
* PUT /api/employees/102 — Editar colaborador
* DELETE /api/employees/102 — Excluir colaborador