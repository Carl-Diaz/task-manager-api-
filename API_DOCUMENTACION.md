 Task Manager API 
 Descripción
API RESTful para gestión de proyectos y tareas con autenticación JWT. Desarrollada en Laravel.

Base URL
text
http://localhost:8001/api
Autenticación
JWT Bearer Token

Incluir en headers: Authorization: Bearer {token}

 Endpoints
Autenticación
1. Registrar usuario
text
POST /register
Body:

json
{
    "name": "Usuario",
    "email": "usuario@email.com",
    "password": "contraseña",
    "password_confirmation": "contraseña"
}
2. Iniciar sesión
text
POST /login
Body:

json
{
    "email": "usuario@email.com",
    "password": "contraseña"
}
Respuesta:

json
{
    "success": true,
    "token": "jwt_token_here"
}
3. Cerrar sesión
text
POST /logout
Headers: Authorization: Bearer {token}

 Proyectos
4. Listar proyectos
text
GET /projects
Headers: Authorization: Bearer {token}

5. Crear proyecto
text
POST /projects
Body:

json
{
    "name": "Nombre proyecto",
    "description": "Descripción"
}
6. Actualizar proyecto
text
PUT /projects/{id}
Body:

json
{
    "name": "Nuevo nombre",
    "description": "Nueva descripción"
}
7. Eliminar proyecto
text
DELETE /projects/{id}
8. Archivar proyecto
text
POST /projects/{id}/archive
 Tareas
9. Listar tareas de proyecto
text
GET /projects/{projectId}/tasks
10. Crear tarea
text
POST /projects/{projectId}/tasks
Body:

json
{
    "title": "Título tarea",
    "description": "Descripción",
    "due_date": "2024-12-31",
    "priority": 2
}
Prioridades: 1=Baja, 2=Media, 3=Alta

11. Actualizar tarea
text
PUT /projects/{projectId}/tasks/{taskId}
12. Completar tarea
text
POST /projects/{projectId}/tasks/{taskId}/complete
13. Eliminar tarea
text
DELETE /projects/{projectId}/tasks/{taskId}
Códigos de estado
Código	Descripción
200	OK - Petición exitosa
201	Created - Recurso creado
400	Bad Request - Datos inválidos
401	Unauthorized - No autenticado
403	Forbidden - Sin permisos
404	Not Found - Recurso no existe
422	Unprocessable Entity - Validación falló
500	Internal Server Error
🛠️ Tecnologías
PHP 8+ con Laravel 10

MySQL / PostgreSQL

JWT para autenticación

CORS habilitado

 Estructura respuesta típica
json
{
    "success": true,
    "message": "Mensaje descriptivo",
    "data": { ... },
    "stats": { ... }
}
🔧 Variables de entorno (.env)
env
APP_URL=http://localhost:8001
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=


🚀 Instalación rápida
bash
# 1. Clonar repositorio
git clone https://github.com/Carl-Diaz/task-manager-api-.git

# 2. Instalar dependencias
composer install

# 3. Configurar entorno
cp .env.example .env

# 4. Generar clave
php artisan key:generate




# 6. Iniciar servidor
php artisan serve --port=8001


Autor: CARLOS DIAZ

