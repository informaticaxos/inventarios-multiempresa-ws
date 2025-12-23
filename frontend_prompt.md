# Prompt para Crear un Frontend para la API de Inventarios Multiempresa

## Descripción del Proyecto
Crea un frontend web moderno y responsivo que consuma la API REST de inventarios multiempresa. La aplicación debe permitir gestionar usuarios, empresas, bodegas, productos y futuras entidades. Incluye autenticación, navegación intuitiva y manejo de errores.

## Tecnologías Sugeridas
- **Framework**: React.js (con hooks y context para estado)
- **Routing**: React Router
- **HTTP Client**: Axios
- **UI Library**: Material-UI (MUI) o Bootstrap para componentes estilizados
- **Estado Global**: Context API o Redux Toolkit
- **Autenticación**: JWT tokens almacenados en localStorage
- **Notificaciones**: Toast notifications (react-toastify)
- **Validación de Formularios**: Formik + Yup
- **Build Tool**: Vite o Create React App

## Estructura del Proyecto
```
src/
├── components/
│   ├── common/ (Header, Sidebar, Footer, etc.)
│   ├── auth/ (LoginForm, etc.)
│   ├── users/ (UserList, UserForm, etc.)
│   ├── companies/ (CompanyList, CompanyForm, etc.)
│   ├── stores/ (StoreList, StoreForm, etc.)
│   ├── products/ (ProductList, ProductForm, etc.)
│   └── ...
├── pages/ (Login, Dashboard, etc.)
├── services/ (api.js para Axios config y endpoints)
├── context/ (AuthContext, etc.)
├── utils/ (helpers, constants)
└── App.js
```

## Configuración de Axios
En `services/api.js`:
```javascript
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000',
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor para agregar token JWT
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Interceptor para manejar errores
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Redirigir a login
      localStorage.removeItem('token');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
```

## Endpoints y Implementación en el Frontend

### 1. Autenticación
#### Login
- **Endpoint**: POST /login
- **Body**: `{ "username": "string", "password": "string" }`
- **Respuesta Exitosa**: `{ "state": 1, "message": "Login successful.", "data": [{ "id_user": number }] }`
- **Implementación**:
  - Crea un componente `LoginForm` con campos username y password.
  - Al enviar, llama `api.post('/login', { username, password })`.
  - Si `state === 1`, guarda el token (si la API lo devuelve) y redirige al dashboard.
  - Maneja errores mostrando mensajes.

### 2. Usuarios (Users)
#### Crear Usuario
- **Endpoint**: POST /users
- **Body**: `{ "dni_user": "string", "username_user": "string", "email_user": "string", "password_user": "string" }`
- **Respuesta**: `{ "state": 1|0, "message": "string", "data": [] }`
- **Implementación**: Formulario con validación. Muestra mensaje de éxito/error.

#### Listar Usuarios
- **Endpoint**: GET /users
- **Respuesta**: `{ "state": 1, "message": "Users found.", "data": [array of users] }`
- **Implementación**: Tabla o lista de usuarios. Carga al montar el componente.

#### Obtener Usuario por ID
- **Endpoint**: GET /users/{id}
- **Respuesta**: `{ "state": 1, "message": "User found.", "data": [user] }`
- **Implementación**: Para editar, carga datos en el formulario.

#### Actualizar Usuario
- **Endpoint**: PUT /users/{id}
- **Body**: Similar a crear, sin password si no se cambia.
- **Implementación**: Formulario pre-llenado, envía PUT.

#### Eliminar Usuario
- **Endpoint**: DELETE /users/{id}
- **Implementación**: Botón de eliminar con confirmación.

### 3. Empresas (Companies)
#### Crear Empresa
- **Endpoint**: POST /companies
- **Body**: `{ "dni_company": "string", "name_company": "string", "phone_company": "string", "email_company": "string", "address_company": "string" }`
- **Respuesta**: `{ "state": 1|0, "message": "string", "data": [] }`
- **Implementación**: Formulario con campos requeridos (dni y name).

#### Listar Empresas
- **Endpoint**: GET /companies
- **Respuesta**: `{ "state": 1, "message": "Companies found.", "data": [array] }`
- **Implementación**: Lista o tabla.

#### Obtener Empresa por ID
- **Endpoint**: GET /companies/{id}
- **Implementación**: Para edición.

#### Actualizar Empresa
- **Endpoint**: PUT /companies/{id}
- **Body**: Campos a actualizar.

#### Eliminar Empresa
- **Endpoint**: DELETE /companies/{id}

### 4. Bodegas (Stores)
#### Crear Bodega
- **Endpoint**: POST /stores
- **Body**: `{ "code_store": "string", "name_store": "string", "address_store": "string", "location_store": "string", "fk_id_company": number }`
- **Respuesta**: `{ "state": 1|0, "message": "string", "data": [] }`
- **Implementación**: Dropdown para seleccionar empresa (carga /companies).

#### Listar Bodegas
- **Endpoint**: GET /stores
- **Respuesta**: `{ "state": 1, "message": "Stores found.", "data": [array] }`

#### Obtener Bodega por ID
- **Endpoint**: GET /stores/{id}

#### Actualizar Bodega
- **Endpoint**: PUT /stores/{id}

#### Eliminar Bodega
- **Endpoint**: DELETE /stores/{id}

### 5. Productos (Products)
#### Crear Producto
- **Endpoint**: POST /products
- **Body**: `{ "code_product": "string", "name_product": "string", "description_product": "string", "pvp_product": number, "min_product": number, "max_product": number, "state_product": number }`
- **Respuesta**: `{ "state": 1|0, "message": "string", "data": [] }`
- **Implementación**: Campos numéricos para precios.

#### Listar Productos
- **Endpoint**: GET /products
- **Respuesta**: `{ "state": 1, "message": "Products found.", "data": [array] }`

#### Obtener Producto por ID
- **Endpoint**: GET /products/{id}

#### Actualizar Producto
- **Endpoint**: PUT /products/{id}

#### Eliminar Producto
- **Endpoint**: DELETE /products/{id}

## Funcionalidades Adicionales
- **Navegación**: Sidebar con enlaces a cada sección (solo si autenticado).
- **Protección de Rutas**: Componente `PrivateRoute` que verifica token.
- **Dashboard**: Página principal con estadísticas (e.g., contar usuarios, empresas).
- **Manejo de Errores**: Mostrar mensajes de error en toasts.
- **Responsive**: Diseño móvil-friendly.
- **Logout**: Botón para limpiar token y redirigir a login.

## Próximos Pasos
Una vez implementado lo básico, agrega endpoints para las tablas restantes (user_company, client, store_product, etc.) siguiendo el patrón. Integra funcionalidades avanzadas como búsquedas, filtros y reportes.

¡Implementa este prompt paso a paso para tener un frontend completo!