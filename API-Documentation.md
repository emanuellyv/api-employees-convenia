
# 📘 Documentação da API - Employees API

> 💡 A variável {{urlLocal}} está definida como http://127.0.0.1:8000/. Certifique-se de que a aplicação esteja rodando nesse endereço (php artisan serve).
---

## 🔐 Autenticação

Os endpoints requerem autenticação via token Bearer.

**Cabeçalho necessário:**
```
Authorization: Bearer {token}
```

O token é obtido através do endpoint de **Login**.

---

## 🔑 Auth

### 🔸 Login

- **Método:** `POST`
- **Endpoint:** `/manager/login`
- **Descrição:** Autentica o gerente e retorna um token.

**Body (JSON):**
```json
{
  "email": "manager-teste@gmail.com",
  "password": "123456abc"
}
```

**Resposta esperada:**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

---

### 🔸 Logout

- **Método:** `POST`
- **Endpoint:** `/logout`
- **Autenticado:** ✅
- **Descrição:** Encerra a sessão do usuário autenticado, invalidando o token.

**Resposta esperada:**
```json
{
  "message": "Logout realizado com sucesso."
}
```

---

## 👤 Employee

### 🔸 Listar todos os colaboradores

- **Método:** `GET`
- **Endpoint:** `/employees`
- **Autenticado:** ✅

---

### 🔸 Buscar colaborador por ID

- **Método:** `GET`
- **Endpoint:** `/employees/{id}`
- **Autenticado:** ✅

---

### 🔸 Adicionar colaborador

- **Método:** `POST`
- **Endpoint:** `/employees`
- **Autenticado:** ✅

**Body (JSON):**
```json
{
  "name": "Colaborador 2",
  "email": "colaborador2-teste@gmail.com",
  "cpf": "000.000.000-02",
  "city": "Curitiba",
  "state": "Paraná"
}
```

---

### 🔸 Editar colaborador

- **Método:** `PUT`
- **Endpoint:** `/employees/{id}`
- **Autenticado:** ✅

**Body (JSON):**
```json
{
  "name": "Colaborador Editado",
  "email": "colaborador-editado-teste@gmail.com",
  "cpf": "000.000.000-00",
  "city": "Curitiba",
  "state": "Paraná"
}
```

---

### 🔸 Remover colaborador

- **Método:** `DELETE`
- **Endpoint:** `/employees/{id}`
- **Autenticado:** ✅

---

### 🔸 Importar colaboradores via CSV

- **Método:** `POST`
- **Endpoint:** `/employees/import`
- **Autenticado:** ✅
- **Descrição:** Importa colaboradores a partir de um arquivo `.csv`.

**Body (form-data):**
- `file` (arquivo CSV)

---

## 🧑‍💼 Manager

### 🔸 Listar todos os gerentes

- **Método:** `GET`
- **Endpoint:** `/managers`
- **Autenticado:** ✅

---

### 🔸 Buscar gerente por ID

- **Método:** `GET`
- **Endpoint:** `/managers/{id}`
- **Autenticado:** ✅

---

### 🔸 Adicionar gerente

- **Método:** `POST`
- **Endpoint:** `/managers`

**Body (JSON):**
```json
{
  "name": "Emanuelly",
  "email": "emanuellyvalenga.dev@mgial.com",
  "password": "123456"
}
```

---

### 🔸 Editar gerente

- **Método:** `PUT`
- **Endpoint:** `/managers/{id}`

**Body (JSON):**
```json
{
  "name": "Teste",
  "email": "teste@teste.com"
}
```

---

### 🔸 Remover gerente

- **Método:** `DELETE`
- **Endpoint:** `/managers/{id}`
