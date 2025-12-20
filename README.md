# Inventarios Multiempresa Backend

Este es el backend desarrollado en PHP para el sistema de inventarios multiempresa.

## Estructura del Proyecto

- `src/Config/` - Configuraciones de base de datos
- `src/Models/` - Modelos de datos
- `src/Controllers/` - Controladores de la API
- `src/Utils/` - Utilidades
- `public/` - Punto de entrada público
- `vendor/` - Dependencias (autoload)

## Instalación

1. Clona el repositorio.
2. Asegúrate de tener PHP instalado.
3. Ejecuta `composer install` si tienes Composer, o el autoload ya está configurado.
4. Sube los archivos al hosting en la ruta `nestorcornejo.com/inventarios-multiempresa-ws`.

## API Endpoints

### Usuarios

- `GET /users` - Obtener todos los usuarios
- `GET /users/{id}` - Obtener un usuario por ID
- `POST /users` - Crear un nuevo usuario (JSON: dni_user, username_user, email_user, password_user)
- `PUT /users/{id}` - Actualizar un usuario (JSON: dni_user, username_user, email_user)
- `DELETE /users/{id}` - Eliminar un usuario

### Login

- `POST /login` - Iniciar sesión (JSON: username, password)

## Notas

- Las contraseñas se hashean automáticamente al crear usuarios.
- La API responde en JSON.
- Configuración de CORS habilitada para desarrollo.