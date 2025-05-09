# Sistema de Gestión de Inventario (InvSys) - Referencia de API

[![Estado](https://img.shields.io/badge/Estado-Activo-success)]()
[![Versión](https://img.shields.io/badge/Versión-1.0-blue)]()
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple)]()

## Índice de Contenidos

- [Introducción](#introducción)
- [Módulos](#módulos)
  - [Productos](#módulo-de-productos)
  - [Inventario](#módulo-de-inventario)
  - [Usuarios](#módulo-de-usuarios)
- [Controladores](#controladores-principales)
- [Utilidades](#funciones-de-utilidad)
- [Estructura de Vistas](#estructura-de-vistas)
- [Convenciones de Código](#convenciones-de-código)

## Introducción

Este documento describe las principales clases y métodos disponibles en el Sistema de Gestión de Inventario (InvSys). Esta referencia está destinada a desarrolladores que necesiten extender o mantener el sistema.

## Módulos

### Módulo de Productos

<details>
<summary><strong>Clase <code>Productos</code></strong></summary>

**Ubicación**: `app/Models/productos/productos.php`

#### Métodos Principales

| Método | Descripción |
|--------|-------------|
| `__construct($conn)` | Inicializa el modelo con la conexión a la base de datos. |
| `validarProducto($id_producto)` | Verifica si un producto existe en la base de datos. |
| `obtenerProductos()` | Retorna todos los productos activos. |
| `obtenerProductosConPaginacion($limit, $offset)` | Retorna productos con paginación para mostrar en listados. |
| `contarTotalProductos()` | Cuenta el número total de productos activos en el sistema. |
| `eliminarProducto($id_producto)` | Realiza una eliminación lógica de un producto (cambia su estado a inactivo). |

```php
// Ejemplo de uso
$productos = new Productos($conn);
$listaProductos = $productos->obtenerProductos();
```
</details>

### Módulo de Inventario

<details>
<summary><strong>Clase <code>Stock</code></strong></summary>

**Ubicación**: `app/Models/stock/stock.php`

#### Métodos Principales

| Método | Descripción |
|--------|-------------|
| `__construct($conn)` | Inicializa el modelo con la conexión a la base de datos. |
| `verInventario($id_almacen)` | Retorna el inventario completo de un almacén específico. |
| `obtenerAlmacenes()` | Retorna la lista de todos los almacenes disponibles. |
| `obtenerMovimientosRecientes()` | Retorna los últimos movimientos de inventario registrados. |
| `obtenerProductosBajoStock()` | Retorna productos que están por debajo del nivel mínimo de stock. |

```php
// Ejemplo de uso
$stock = new Stock($conn);
$inventario = $stock->verInventario(1); // Inventario del almacén con ID 1
```
</details>

### Módulo de Usuarios

<details>
<summary><strong>Clase <code>Usuarios</code></strong></summary>

**Ubicación**: `app/Models/usuarios/usuarios.php`

#### Métodos Principales

| Método | Descripción |
|--------|-------------|
| `__construct($conn)` | Inicializa el modelo con la conexión a la base de datos. |
| `validarUsuario($email, $password)` | Verifica las credenciales de un usuario para el inicio de sesión. |
| `crearUsuario($nombre, $apellido, $email, $password, $nivel_acceso)` | Crea un nuevo usuario en el sistema. |
| `actualizarUsuario($id_usuario, $nombre, $apellido, $email, $nivel_acceso)` | Actualiza la información de un usuario existente. |
| `cambiarPassword($id_usuario, $nueva_password)` | Actualiza la contraseña de un usuario. |

```php
// Ejemplo de uso
$usuarios = new Usuarios($conn);
$resultado = $usuarios->validarUsuario('usuario@ejemplo.com', 'contraseña');
```
</details>

## Controladores Principales

<details>
<summary><strong>Clase <code>ControlInventarioController</code></strong></summary>

**Ubicación**: `app/Controller/stock/verInventarioController.php`

#### Métodos Principales

| Método | Descripción |
|--------|-------------|
| `__construct($conn)` | Inicializa el controlador con la conexión a la base de datos. |
| `verInventario()` | Procesa la solicitud para ver el inventario de un almacén específico. |
| `obtenerListas()` | Obtiene las listas necesarias para mostrar en el formulario de stock. |

```php
// Ejemplo de uso
$controlador = new ControlInventarioController($conn);
$datos = $controlador->verInventario();
```
</details>

<details>
<summary><strong>Clase <code>ListarProductosController</code></strong></summary>

**Ubicación**: `app/Controller/productos/listarProductosController.php`

#### Métodos Principales

| Método | Descripción |
|--------|-------------|
| `__construct($conn)` | Inicializa el controlador con la conexión a la base de datos. |
| `listarProductos()` | Procesa la solicitud para listar productos con paginación. |

```php
// Ejemplo de uso
$controlador = new ListarProductosController($conn);
$datos = $controlador->listarProductos();
```
</details>

## Funciones de Utilidad

<details>
<summary><strong>Función <code>nivelRequerido</code></strong></summary>

**Ubicación**: `config/funciones.php`

```php
nivelRequerido($nivel_minimo)
```

Verifica si el usuario actual tiene el nivel de acceso requerido para acceder a una funcionalidad.

```php
// Ejemplo de uso
if (nivelRequerido(2)) {
    // El usuario tiene acceso a esta funcionalidad
}
```
</details>

<details>
<summary><strong>Clase <code>InventoryChatbot</code></strong></summary>

**Ubicación**: `chatbot_ollama.php`

#### Métodos Principales

| Método | Descripción |
|--------|-------------|
| `__construct()` | Inicializa el chatbot con la conexión a la base de datos y configura el prompt del sistema. |
| `processQuery($query)` | Procesa una consulta en lenguaje natural y devuelve la respuesta. |

```php
// Ejemplo de uso
$chatbot = new InventoryChatbot();
$respuesta = $chatbot->processQuery('¿Cuántos productos tenemos en stock?');
```
</details>

## Estructura de Vistas

Las vistas del sistema se encuentran en la carpeta `app/Views/` y están organizadas por módulos:

| Carpeta | Descripción |
|---------|-------------|
| `productos/` | Vistas relacionadas con la gestión de productos |
| `stock/` | Vistas relacionadas con la gestión de inventario |
| `usuarios/` | Vistas relacionadas con la gestión de usuarios |
| `proveedores/` | Vistas relacionadas con la gestión de proveedores |
| `includes/` | Componentes reutilizables en múltiples vistas |
| `components/` | Componentes de UI específicos |

## Convenciones de Código

### Nomenclatura

| Tipo | Convención | Ejemplo |
|------|------------|--------|
| **Clases** | PascalCase | `ControlInventarioController` |
| **Métodos y funciones** | camelCase | `verInventario()` |
| **Variables** | camelCase | `$totalProductos` |
| **Archivos de clase** | Mismo nombre que la clase | `ControlInventarioController.php` |

### Estructura de Archivos

| Tipo | Ruta |
|------|------|
| **Modelos** | `app/Models/{modulo}/{nombre_modelo}.php` |
| **Controladores** | `app/Controller/{modulo}/{nombre_controlador}.php` |
| **Vistas** | `app/Views/{modulo}/{nombre_vista}.php` |

---

*Sistema de Gestión de Inventario (InvSys) - Referencia de API*  
*Versión 1.0*

[Volver al inicio](#sistema-de-gestión-de-inventario-invsys---referencia-de-api)