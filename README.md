#  Task Manager API

**API RESTful para gestión de proyectos y tareas con autenticación JWT**

[Características](#-características) • [Instalación](#-instalación) • [Uso](#-uso) • [Endpoints](#-endpoints) • [Ejemplos](#-ejemplos)


## ✨ Características

-  **Autenticación JWT** segura
-  **CRUD completo** de proyectos y tareas
-  **Multi-usuario** (cada usuario ve solo sus datos)
-  **Archivado** de proyectos
-  **Completado** de tareas
-  **Prioridades** en tareas (Baja/Media/Alta)
-  **Validación** robusta de datos
-  **Respuestas estandarizadas** JSON
-  **CORS** habilitado
-  **API RESTful** convencional

## 🛠️ Requisitos

- PHP 8.1 o superior
- Composer
- MySQL 5.7+ / PostgreSQL
- Laravel 10

## 🚀 Instalación

### 1. Clonar repositorio

git clone https://github.com/Carl-Diaz/task-manager-api-.git
cd task-manager-api
2. Instalar dependencias

composer install
3. Configurar entorno
cp .env.example .env
php artisan key:generate

4. Configurar base de datos
Editar .env:

env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=

5. Migrar base de datos
php artisan migrate

6. Iniciar servidor
php artisan serve --port=8001
La API estará disponible en: http://localhost:8001/api

⚙️ Configuración
Variables de entorno importantes
env
APP_URL=http://localhost:8001
APP_DEBUG=false

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=



Base URL
http://localhost:8001/api
Headers requeridos
http
Content-Type: application/json
Authorization: Bearer {tu_token_jwt}  # Para endpoints protegidos
Flujo típico
Registrar usuario → /register

Login → /login (obtener token)

Usar token en headers para operaciones CRUD

Logout → /logout (invalidar token)

 Endpoints
 Autenticación
Método	Endpoint	Descripción
POST	/register	Registrar nuevo usuario
POST	/login	Iniciar sesión
POST	/logout	Cerrar sesión

 Proyectos
Método	Endpoint	Descripción
GET	/projects	Listar proyectos del usuario
POST	/projects	Crear nuevo proyecto
PUT	/projects/{id}	Actualizar proyecto
DELETE	/projects/{id}	Eliminar proyecto
POST	/projects/{id}/archive	Archivar proyecto

 Tareas
Método	Endpoint	Descripción
GET	/projects/{id}/tasks	Listar tareas de un proyecto
POST	/projects/{id}/tasks	Crear nueva tarea
PUT	/projects/{id}/tasks/{taskId}	Actualizar tarea
POST	/projects/{id}/tasks/{taskId}/complete	Marcar tarea como completada
DELETE	/projects/{id}/tasks/{taskId}	Eliminar tarea

 Autenticación
Registro
json
POST /api/register
{
    "name": "Juan Pérez",
    "email": "juan@email.com",
    "password": "password123",
    "password_confirmation": "password123"
}
Login
json
POST /api/login
{
    "email": "juan@email.com",
    "password": "password123"
}
Respuesta exitosa:

json
{
    "message": "Inicio de sesión exitoso",
    "user": {
        "id": 4,
        "name": "jube",
        "email": "jube@example.com",
        "email_verified_at": null,
        "created_at": "2025-12-14T16:58:57.000000Z",
        "updated_at": "2025-12-14T16:58:57.000000Z"
    },
    "token": "26|ADDaLTrGTTynIRNMiWcbmVthl0F0IdwsLwT4mLZ88d66e8fb",
    "token_type": "Bearer"
}


 Errores comunes
Código	Error	Solución
401	Unauthorized	Token inválido o expirado
403	Forbidden	No tiene permisos
404	Not Found	Recurso no existe
422	Validation Error	Datos inválidos en request
500	Server Error	Error interno del servidor
Ejemplo error 422:

json
{
    "success": false,
    "message": "Error de validación",
    "errors": {
        "email": ["El email ya está registrado"],
        "password": ["La contraseña debe tener al menos 8 caracteres"]
    }
}
 Contribuir
Fork el repositorio

Crear rama: git checkout -b feature/nueva-funcionalidad

Commit cambios: git commit -m 'Agrega nueva funcionalidad'

Push: git push origin feature/nueva-funcionalidad

Abrir Pull Request


Autor: CARLOS DIAZ