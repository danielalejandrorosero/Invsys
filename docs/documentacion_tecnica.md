# Documentación Técnica - Sistema de Gestión de Inventario

## Índice
1. [Introducción](#introducción)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Estructura de la Base de Datos](#estructura-de-la-base-de-datos)
4. [Módulos del Sistema](#módulos-del-sistema)
5. [Flujo de Trabajo](#flujo-de-trabajo)
6. [Requisitos Técnicos](#requisitos-técnicos)
7. [Seguridad](#seguridad)

## Introducción

El Sistema de Gestión de Inventario (InvSys) es una aplicación web desarrollada para administrar eficientemente el inventario de productos en múltiples almacenes. El sistema permite realizar seguimiento de stock, gestionar productos, proveedores, y generar alertas cuando los niveles de inventario están por debajo del mínimo establecido.

## Arquitectura del Sistema

El sistema está desarrollado siguiendo el patrón de arquitectura Modelo-Vista-Controlador (MVC):

### Estructura de Directorios

```
InventoryManagementSystem/
├── app/
│   ├── Controller/       # Controladores que manejan la lógica de negocio
│   ├── Models/           # Modelos que interactúan con la base de datos
│   └── Views/            # Vistas que presentan la interfaz al usuario
├── config/               # Archivos de configuración
├── frontend/            # Estilos CSS específicos
├── public/              # Punto de entrada y recursos públicos
│   ├── css/
│   ├── js/
│   └── uploads/
└── vendor/              # Dependencias de terceros (Composer)
```

### Flujo de Datos

1. El usuario interactúa con las vistas (Views)
2. Las solicitudes son procesadas por los controladores (Controller)
3. Los controladores utilizan los modelos (Models) para interactuar con la base de datos
4. Los resultados son devueltos a las vistas para su presentación

## Estructura de la Base de Datos

El sistema utiliza una base de datos MySQL con las siguientes tablas principales:

### Tablas Principales

- **productos**: Almacena información de los productos
  - id_producto (PK)
  - nombre
  - descripcion
  - precio_compra
  - precio_venta
  - stock_minimo
  - stock_maximo
  - id_categoria (FK)
  - id_proveedor (FK)
  - estado

- **stock_almacen**: Registra el inventario de productos por almacén
  - id_stock (PK)
  - id_almacen (FK)
  - id_producto (FK)
  - cantidad_disponible

- **almacenes**: Información de los almacenes
  - id_almacen (PK)
  - nombre
  - ubicacion

- **categorias**: Categorías de productos
  - id_categoria (PK)
  - nombre
  - descripcion

- **proveedores**: Información de proveedores
  - id_proveedor (PK)
  - nombre
  - contacto
  - telefono
  - email

- **usuarios**: Usuarios del sistema
  - id_usuario (PK)
  - nombre
  - apellido
  - email
  - password
  - nivel_acceso

- **movimientos_inventario**: Registro de movimientos de inventario
  - id_movimiento (PK)
  - id_producto (FK)
  - id_almacen (FK)
  - tipo_movimiento
  - cantidad
  - fecha_movimiento
  - id_usuario (FK)

- **alertas_stock**: Alertas de stock bajo
  - id_alerta (PK)
  - id_producto (FK)
  - id_almacen (FK)
  - mensaje
  - fecha_alerta
  - estado

### Diagrama de Relaciones

```
productos ──┬── stock_almacen ──── almacenes
            │
            ├── categorias
            │
            └── proveedores

usuarios ──── movimientos_inventario

productos ──── alertas_stock ──── almacenes
```

## Módulos del Sistema

### 1. Módulo de Productos

**Funcionalidades:**
- Crear, editar y eliminar productos
- Asignar categorías y proveedores
- Establecer precios de compra y venta
- Definir niveles de stock mínimo y máximo
- Listar productos con paginación

**Archivos principales:**
- Models/productos/productos.php
- Controller/productos/listarProductosController.php
- Views/productos/listarProductosView.php

### 2. Módulo de Inventario

**Funcionalidades:**
- Visualizar inventario por almacén
- Ajustar niveles de stock
- Registrar movimientos de entrada y salida
- Alertas de stock bajo
- Transferencias entre almacenes

**Archivos principales:**
- Models/stock/stock.php
- Controller/stock/verInventarioController.php
- Views/stock/verInventario.php
- Views/stock/ajustarStock.php

### 3. Módulo de Usuarios

**Funcionalidades:**
- Gestión de usuarios
- Control de acceso basado en roles
- Autenticación y autorización
- Recuperación de contraseñas

**Archivos principales:**
- Models/usuarios/usuarios.php
- Controller/usuarios/usuariosController.php
- Views/usuarios/listaUsuarios.php

### 4. Módulo de Proveedores

**Funcionalidades:**
- Gestión de proveedores
- Asociación de productos con proveedores
- Historial de compras por proveedor

**Archivos principales:**
- Models/proveedor/proveedor.php
- Controller/proveedores/proveedoresController.php
- Views/proveedores/listarProveedores.php

### 5. Módulo de Reportes

**Funcionalidades:**
- Generación de informes de inventario
- Reportes de movimientos
- Estadísticas de productos
- Valoración de inventario

### 6. Chatbot de Asistencia

**Funcionalidades:**
- Consultas de inventario mediante lenguaje natural
- Identificación de productos con stock bajo
- Cálculo de valor del inventario
- Generación de informes básicos

**Archivos principales:**
- chatbot_ollama.php

## Flujo de Trabajo

### Gestión de Inventario

1. **Visualización de Inventario**
   - El usuario selecciona un almacén
   - El sistema muestra los productos disponibles con sus cantidades
   - Se destacan los productos con stock bajo

2. **Ajuste de Stock**
   - El usuario selecciona un producto
   - Ingresa la cantidad a ajustar (entrada o salida)
   - El sistema registra el movimiento y actualiza el inventario

3. **Alertas Automáticas**
   - El sistema verifica periódicamente los niveles de stock
   - Genera alertas cuando un producto está por debajo del mínimo
   - Las alertas se muestran en el panel de control

## Requisitos Técnicos

### Servidor
- PHP 8.0 o superior
- MySQL 5.7 o superior
- Servidor web Apache/Nginx

### Dependencias
- Composer para gestión de paquetes
- Biblioteca Dotenv para variables de entorno
- Materialize CSS para la interfaz de usuario
- Font Awesome para iconos

### Configuración

El sistema utiliza variables de entorno para la configuración de la base de datos y otras opciones:

```php
// Ejemplo de archivo .env
DB_SERVER=localhost
DB_USERNAME=usuario
DB_PASSWORD=contraseña
DB_DATABASE=ims_invsys
TIMEZONE=America/Bogota
```

## Seguridad

### Autenticación y Autorización

- Sistema de login con contraseñas hasheadas
- Control de acceso basado en niveles de usuario
- Protección contra inyección SQL mediante consultas preparadas
- Validación de datos de entrada

### Niveles de Acceso

1. **Nivel 1**: Administrador (acceso completo)
2. **Nivel 2**: Supervisor (gestión de inventario y reportes)
3. **Nivel 3**: Operador (consulta de inventario y registro de movimientos)

### Protección de Rutas

Todas las páginas del sistema están protegidas mediante la función `nivelRequerido()` que verifica si el usuario tiene el nivel de acceso necesario para acceder a la funcionalidad.

```php
nivelRequerido(1); // Requiere nivel de administrador
```

---

*Documentación generada para el Sistema de Gestión de Inventario (InvSys)*