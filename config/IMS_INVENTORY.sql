-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-05-2025 a las 01:44:49
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ims_invsys`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas_stock`
--

CREATE TABLE `alertas_stock` (
  `id_alerta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_almacen` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_alerta` timestamp NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','enviada') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `alertas_stock`
--

INSERT INTO `alertas_stock` (`id_alerta`, `id_producto`, `id_almacen`, `mensaje`, `fecha_alerta`, `estado`) VALUES
(1, 12, 1, 'Stock bajo (1 disponibles, mínimo 5)', '2025-03-15 17:04:30', 'pendiente'),
(2, 3, 1, 'Stock bajo (1 disponibles, mínimo 15)', '2025-03-15 17:04:30', 'pendiente'),
(3, 2, 2, 'Stock bajo (1 disponibles, mínimo 20)', '2025-03-15 17:04:30', 'pendiente'),
(4, 3, 2, 'Stock bajo (1 disponibles, mínimo 15)', '2025-03-15 17:04:30', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacenes`
--

CREATE TABLE `almacenes` (
  `id_almacen` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ubicacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `almacenes`
--

INSERT INTO `almacenes` (`id_almacen`, `nombre`, `ubicacion`) VALUES
(1, 'Almacén Central', 'Bogotá, Carrera 10 #20-30'),
(2, 'Almacén Norte', 'Medellín, Calle 50 #80-45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Electrónica', 'Dispositivos electrónicos y accesorios'),
(2, 'Ropa', 'Prendas de vestir para todas las edades'),
(3, 'Hogar', 'Artículos para el hogar y decoración');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat_logs`
--

CREATE TABLE `chat_logs` (
  `id` int(11) NOT NULL,
  `user_input` text NOT NULL,
  `bot_response` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `chat_logs`
--

INSERT INTO `chat_logs` (`id`, `user_input`, `bot_response`, `timestamp`, `user_id`) VALUES
(1, 'Hola', '**Consulta**: SELECT * FROM stock_almacen;\n\n**Explicación**: Esta consulta consulta todas las columnas de la tabla `stock_almacen` para todos los productos en el sistema.\n\n**Resultado**:\n\n```sql\nid_producto | nombre | cantidad | precio_unitario | fecha_ultima_actualizacion\n------- | -------- | -------- | -------------- | ---------------------\n1 | Producto A | 10 | 100 | 2023-03-01 10:00:00\n2 | Producto B | 5 | 200 | 2023-03-02 12:00:00\n3 | Producto C | 15 | 300 | 2023-03-03 08:00:00\n```\n**Resultado**: [{\"id_stock\":1,\"id_almacen\":1,\"id_producto\":12,\"cantidad_disponible\":111},{\"id_stock\":2,\"id_almacen\":1,\"id_producto\":3,\"cantidad_disponible\":111},{\"id_stock\":3,\"id_almacen\":2,\"id_producto\":2,\"cantidad_disponible\":133},{\"id_stock\":4,\"id_almacen\":2,\"id_producto\":3,\"cantidad_disponible\":471},{\"id_stock\":7,\"id_almacen\":2,\"id_producto\":1,\"cantidad_disponible\":10011},{\"id_stock\":10,\"id_almacen\":1,\"id_producto\":1,\"cantidad_disponible\":989},{\"id_stock\":18,\"id_almacen\":1,\"id_producto\":2,\"cantidad_disponible\":11100},{\"id_stock\":25,\"id_almacen\":1,\"id_producto\":18,\"cantidad_disponible\":111}]', '2025-05-08 23:28:43', NULL),
(2, 'Hola', '**Consulta**: SELECT * FROM stock_almacen;\n\n**Explicación**: Esta consulta selecciona todos los registros de la tabla `stock_almacen` y devuelve los valores de todas las columnas.\n\n**Resultado**: [Tabla con los datos del inventario]\n**Resultado**: [{\"id_stock\":1,\"id_almacen\":1,\"id_producto\":12,\"cantidad_disponible\":111},{\"id_stock\":2,\"id_almacen\":1,\"id_producto\":3,\"cantidad_disponible\":111},{\"id_stock\":3,\"id_almacen\":2,\"id_producto\":2,\"cantidad_disponible\":133},{\"id_stock\":4,\"id_almacen\":2,\"id_producto\":3,\"cantidad_disponible\":471},{\"id_stock\":7,\"id_almacen\":2,\"id_producto\":1,\"cantidad_disponible\":10011},{\"id_stock\":10,\"id_almacen\":1,\"id_producto\":1,\"cantidad_disponible\":989},{\"id_stock\":18,\"id_almacen\":1,\"id_producto\":2,\"cantidad_disponible\":11100},{\"id_stock\":25,\"id_almacen\":1,\"id_producto\":18,\"cantidad_disponible\":111}]', '2025-05-08 23:33:33', NULL),
(3, 'hola', '¡Encantado de ayudarte! Estoy listo para ayudarte con las consultas y alertas en el sistema IMS_invsys. ¿Qué puedo hacer para usted hoy?', '2025-05-08 23:36:03', NULL),
(4, 'hola', '**Consulta**: SELECT * FROM `stock_almacen` WHERE `fecha` BETWEEN \'2023-01-01\' AND \'2023-12-31\'\n\n**Explicación**: Esta consulta selecciona todos los registros de la tabla `stock_almacen` que se hayan almacenado entre el 1 de enero y el 31 de diciembre de 2023.\n\n**Resultado**: ```\n| ID | Fecha | Producto | Cantidad | Precio |\r\n|---|---|---|---|---|\n| 1 | 01/01/2023 | Producto A | 10 | 100 |\n| 2 | 01/01/2023 | Producto B | 5 | 50 |\n| 3 | 31/12/2023 | Producto A | 15 | 120 |\n| 4 | 31/12/2023 | Producto C | 10 | 200 |\n```', '2025-05-08 23:41:53', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `email`, `telefono`, `direccion`) VALUES
(1, 'Cliente Uno', 'cliente1@example.com', '3112223344', 'Calle 123 #45-67, Bogotá'),
(2, 'Cliente Dos', 'cliente2@example.com', '3155556677', 'Avenida Siempre Viva 742, Medellín');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `fecha_compra` timestamp NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','en proceso','recibido','cancelado') DEFAULT 'pendiente',
  `total` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `id_proveedor`, `fecha_compra`, `estado`, `total`) VALUES
(1, 1, '2025-03-12 22:05:59', 'recibido', 2400000.00),
(2, 2, '2025-03-12 22:05:59', 'pendiente', 5000000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compras`
--

CREATE TABLE `detalle_compras` (
  `id_detalle` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_compras`
--

INSERT INTO `detalle_compras` (`id_detalle`, `id_compra`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 3, 800000.00),
(2, 1, 2, 100, 50000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id_detalle` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id_detalle`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 1, 1200000.00),
(2, 1, 2, 10, 85000.00),
(3, 1, 3, 1, 180000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id_grupo` int(11) NOT NULL,
  `nombre_grupo` varchar(255) NOT NULL,
  `nivel_grupo` int(11) NOT NULL,
  `estado_grupo` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: activo, 0: inactivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre_grupo`, `nivel_grupo`, `estado_grupo`) VALUES
(1, 'Admin', 1, 1),
(2, 'User', 2, 1),
(3, 'supervisor', 3, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_productos`
--

CREATE TABLE `imagenes_productos` (
  `id_imagen` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre_imagen` varchar(255) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `imagenes_productos`
--

INSERT INTO `imagenes_productos` (`id_imagen`, `id_producto`, `nombre_imagen`, `ruta_imagen`) VALUES
(7, 1, 'e12564e38efe1dbbed444d372487db2101d5b2f9c7d82bde89cd4f4e462b1a67.png', '/srv/http/InventoryManagementSystem/app/Controller/subirImagenes/../../../public/uploads/imagenes/productos/e12564e38efe1dbbed444d372487db2101d5b2f9c7d82bde89cd4f4e462b1a67.png'),
(8, 1, '3d17deeb0ae5d270c934a53ec6df1a4288d2016491ec11f4fd2d740334eb9590.png', '/srv/http/InventoryManagementSystem/app/Controller/subirImagenes/../../../public/uploads/imagenes/productos/3d17deeb0ae5d270c934a53ec6df1a4288d2016491ec11f4fd2d740334eb9590.png'),
(9, 1, '63ad90449c109cc929d2c4fb700a3df772b8e42e199e52b6c79a6a472ff76fd6.png', '/srv/http/InventoryManagementSystem/app/Controller/subirImagenes/../../../public/uploads/imagenes/productos/63ad90449c109cc929d2c4fb700a3df772b8e42e199e52b6c79a6a472ff76fd6.png'),
(10, 1, 'c42ad814b166cb2357e7b8d13c557aa5e378387d286ce8e8df9c0169e277e266.png', '/srv/http/InventoryManagementSystem/app/Controller/subirImagenes/../../../public/uploads/imagenes/productos/c42ad814b166cb2357e7b8d13c557aa5e378387d286ce8e8df9c0169e277e266.png'),
(11, 1, '137febe302a96b3fe15b4cdb423013bdca573188885ec1dfd5df5f47502f44a7.png', '/srv/http/InventoryManagementSystem/app/Controller/subirImagenes/../../../public/uploads/imagenes/productos/137febe302a96b3fe15b4cdb423013bdca573188885ec1dfd5df5f47502f44a7.png'),
(12, 3, 'b5a8d4fda6ba0fda53ffab6830f64da34b4e2a22d8465d6259d72905aaedc7b4.png', '/srv/http/InventoryManagementSystem/app/Controller/subirImagenes/../../../public/uploads/imagenes/productos/b5a8d4fda6ba0fda53ffab6830f64da34b4e2a22d8465d6259d72905aaedc7b4.png'),
(13, 2, '46f70a7ce1b731dc7c72631023ff885546b27ee5720352ae5a1d671164718386.jpeg', 'C:\\xampp\\htdocs\\InventoryManagementSystem\\app\\Controller\\subirImagenes/../../../public/uploads/imagenes/productos/46f70a7ce1b731dc7c72631023ff885546b27ee5720352ae5a1d671164718386.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_usuarios`
--

CREATE TABLE `imagenes_usuarios` (
  `id_imagen` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_imagen` varchar(255) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `imagenes_usuarios`
--

INSERT INTO `imagenes_usuarios` (`id_imagen`, `id_usuario`, `nombre_imagen`, `ruta_imagen`) VALUES
(1, 47, 'd0ea246cd7890d4403147ad8b6b503fa0fda909554b679a8720aa9ab2a6f3bb4.jpeg', 'C:\\xampp\\htdocs\\InventoryManagementSystem\\app\\Controller\\subirImagenes/../../../public/uploads/imagenes/usuarios/d0ea246cd7890d4403147ad8b6b503fa0fda909554b679a8720aa9ab2a6f3bb4.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_stock`
--

CREATE TABLE `movimientos_stock` (
  `id_movimiento` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_almacen_origen` int(11) DEFAULT NULL,
  `id_almacen_destino` int(11) DEFAULT NULL,
  `tipo_movimiento` enum('entrada','salida','transferencia') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_movimiento` timestamp NULL DEFAULT current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `movimientos_stock`
--

INSERT INTO `movimientos_stock` (`id_movimiento`, `id_producto`, `id_almacen_origen`, `id_almacen_destino`, `tipo_movimiento`, `cantidad`, `fecha_movimiento`, `id_usuario`) VALUES
(1, 1, 2, 1, 'entrada', 1, '2025-03-12 22:05:59', 47),
(3, 3, 1, 2, 'transferencia', 1, '2025-03-12 22:05:59', 47),
(7, 3, 2, 1, 'transferencia', 1, '2025-03-17 01:47:44', 47),
(12, 3, 1, 2, 'transferencia', 3, '2025-04-15 06:49:11', 47),
(13, 3, 1, 2, 'transferencia', 1, '2025-04-15 06:53:58', 47),
(14, 3, 1, 2, 'transferencia', 11, '2025-04-15 06:54:17', 47),
(15, 3, 1, 2, 'transferencia', 1, '2025-04-15 14:00:25', 47),
(16, 3, 1, 2, 'transferencia', 1, '2025-04-16 06:24:00', 47),
(17, 2, 2, 1, 'transferencia', 1, '2025-04-16 06:24:58', 47),
(18, 2, 2, 1, 'transferencia', 1, '2025-04-16 06:25:03', 47),
(19, 1, 2, 1, 'transferencia', 1111, '2025-05-04 15:49:50', 47),
(20, 1, 1, 2, 'transferencia', 111, '2025-05-04 16:59:56', 47),
(21, 1, 1, 2, 'transferencia', 11, '2025-05-04 17:52:32', 47),
(22, 2, 1, 2, 'transferencia', 11, '2025-05-04 17:52:43', 47),
(23, 1, 1, 2, 'transferencia', 11, '2025-05-04 20:43:42', 47),
(24, 2, 1, 2, 'transferencia', 11, '2025-05-05 04:01:18', 47);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `id_unidad_medida` int(11) NOT NULL DEFAULT 1,
  `stock_minimo` int(11) DEFAULT 0,
  `stock_maximo` int(11) DEFAULT 0,
  `id_categoria` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_proveedor` int(11) DEFAULT NULL,
  `estado` enum('activo','eliminado') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `codigo`, `sku`, `descripcion`, `precio_compra`, `precio_venta`, `id_unidad_medida`, `stock_minimo`, `stock_maximo`, `id_categoria`, `fecha_creacion`, `fecha_actualizacion`, `id_proveedor`, `estado`) VALUES
(1, 'Smartphone X', 'PROD001', 'SKU12345', 'Teléfono inteligente última generación', 800000.00, 1200000.00, 2, 5, 100, 1, '2025-03-12 22:05:59', '2025-05-06 02:07:06', 2, 'eliminado'),
(2, 'Camisa Algodón', 'PROD002', 'SKU54321', 'Camisa de algodón talla M', 50000.00, 85000.00, 1, 20, 1, 2, '2025-03-12 22:05:59', '2025-05-06 02:07:23', 2, 'activo'),
(3, 'Juego de Sábanas', 'PROD003', 'SKU67890', 'Sábanas de algodón egipcio', 120000.00, 180000.00, 1, 15, 20, 3, '2025-03-12 22:05:59', '2025-04-16 05:42:20', 1, 'activo'),
(12, 'ProductoPrueba', 'PROD9999', 'SKU999999', 'Este es un producto de prueba', 1000.50, 1500.75, 1, 5, 50, 1, '2025-03-13 03:20:41', '2025-04-15 16:58:45', 1, 'activo'),
(18, 'jabon', 'wqdwqd', 'HOLA', 'ddwqd', 1000.00, 1000.00, 2, 2, 2, 2, '2025-04-15 01:17:18', '2025-04-17 01:03:22', 1, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `contacto` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `direccion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre`, `contacto`, `telefono`, `email`, `direccion`) VALUES
(1, 'Proveedor Tech', 'Juan Pérez', '6012345678', 'proveedor.tech@example.com', 'Carrera 7 #40-50, Bogotá'),
(2, 'Proveedor Ropa', 'María Gómez', '6054321098', 'proveedor.ropa@example.com', 'Calle 80 #10-20, Medellín');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_almacen`
--

CREATE TABLE `stock_almacen` (
  `id_stock` int(11) NOT NULL,
  `id_almacen` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad_disponible` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `stock_almacen`
--

INSERT INTO `stock_almacen` (`id_stock`, `id_almacen`, `id_producto`, `cantidad_disponible`) VALUES
(1, 1, 12, 111),
(2, 1, 3, 111),
(3, 2, 2, 133),
(4, 2, 3, 471),
(7, 2, 1, 10011),
(10, 1, 1, 989),
(18, 1, 2, 11100),
(25, 1, 18, 111);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_medida`
--

CREATE TABLE `unidades_medida` (
  `id_unidad` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `unidades_medida`
--

INSERT INTO `unidades_medida` (`id_unidad`, `nombre`) VALUES
(2, 'kg'),
(3, 'litro'),
(4, 'metro'),
(5, 'otro'),
(1, 'unidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `nivel_usuario` int(11) NOT NULL DEFAULT 1,
  `nombreUsuario` varchar(50) NOT NULL,
  `token_recuperacion` varchar(255) DEFAULT NULL,
  `expira_token` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `password`, `status`, `last_login`, `nivel_usuario`, `nombreUsuario`, `token_recuperacion`, `expira_token`) VALUES
(47, 'material', 'danielalejandroroseroortiz80@gmail.com', '$2y$12$i0kHWCiDho0sWAwqgw9OBOPfG295FIvzMsa1EPEJ8.w763zkaOeGC', 1, '2025-03-11 02:54:17', 1, 'root', '2307450b0aea0fabdf18bfa7838fd9e3e90370bc7da267376751100a7020c39c', '2025-04-16 01:30:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_venta` timestamp NULL DEFAULT current_timestamp(),
  `total` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `fecha_venta`, `total`) VALUES
(1, 1, '2025-03-12 22:05:59', 2050000.00),
(2, 2, '2025-03-12 22:05:59', 180000.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alertas_stock`
--
ALTER TABLE `alertas_stock`
  ADD PRIMARY KEY (`id_alerta`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_almacen` (`id_almacen`);

--
-- Indices de la tabla `almacenes`
--
ALTER TABLE `almacenes`
  ADD PRIMARY KEY (`id_almacen`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `chat_logs`
--
ALTER TABLE `chat_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_grupo`),
  ADD UNIQUE KEY `nivel_grupo` (`nivel_grupo`);

--
-- Indices de la tabla `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `imagenes_usuarios`
--
ALTER TABLE `imagenes_usuarios`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_almacen_origen` (`id_almacen_origen`),
  ADD KEY `id_almacen_destino` (`id_almacen_destino`),
  ADD KEY `fk_movimientos_stock_usuarios` (`id_usuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD UNIQUE KEY `unique_sku` (`sku`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `fk_unidad_medida` (`id_unidad_medida`),
  ADD KEY `fk_productos_proveedores` (`id_proveedor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `stock_almacen`
--
ALTER TABLE `stock_almacen`
  ADD PRIMARY KEY (`id_stock`),
  ADD UNIQUE KEY `unique_almacen_producto` (`id_almacen`,`id_producto`),
  ADD KEY `id_almacen` (`id_almacen`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  ADD PRIMARY KEY (`id_unidad`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_nombreUsuario` (`nombreUsuario`),
  ADD KEY `id_grupo` (`nivel_usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alertas_stock`
--
ALTER TABLE `alertas_stock`
  MODIFY `id_alerta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `almacenes`
--
ALTER TABLE `almacenes`
  MODIFY `id_almacen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `chat_logs`
--
ALTER TABLE `chat_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `imagenes_usuarios`
--
ALTER TABLE `imagenes_usuarios`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `stock_almacen`
--
ALTER TABLE `stock_almacen`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  MODIFY `id_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alertas_stock`
--
ALTER TABLE `alertas_stock`
  ADD CONSTRAINT `alertas_stock_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `alertas_stock_ibfk_2` FOREIGN KEY (`id_almacen`) REFERENCES `stock_almacen` (`id_almacen`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `chat_logs`
--
ALTER TABLE `chat_logs`
  ADD CONSTRAINT `chat_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_compras`
--
ALTER TABLE `detalle_compras`
  ADD CONSTRAINT `detalle_compras_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_compras_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_ventas_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `imagenes_productos`
--
ALTER TABLE `imagenes_productos`
  ADD CONSTRAINT `imagenes_productos_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `imagenes_usuarios`
--
ALTER TABLE `imagenes_usuarios`
  ADD CONSTRAINT `imagenes_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  ADD CONSTRAINT `fk_movimientos_stock_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `movimientos_stock_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `movimientos_stock_ibfk_2` FOREIGN KEY (`id_almacen_origen`) REFERENCES `almacenes` (`id_almacen`),
  ADD CONSTRAINT `movimientos_stock_ibfk_3` FOREIGN KEY (`id_almacen_destino`) REFERENCES `almacenes` (`id_almacen`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_proveedores` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_productos_unidad` FOREIGN KEY (`id_unidad_medida`) REFERENCES `unidades_medida` (`id_unidad`),
  ADD CONSTRAINT `fk_unidad_medida` FOREIGN KEY (`id_unidad_medida`) REFERENCES `unidades_medida` (`id_unidad`),
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL;

--
-- Filtros para la tabla `stock_almacen`
--
ALTER TABLE `stock_almacen`
  ADD CONSTRAINT `stock_almacen_ibfk_1` FOREIGN KEY (`id_almacen`) REFERENCES `almacenes` (`id_almacen`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_almacen_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_grupo` FOREIGN KEY (`nivel_usuario`) REFERENCES `grupos` (`nivel_grupo`),
  ADD CONSTRAINT `fk_usuario_grupo_nivel` FOREIGN KEY (`nivel_usuario`) REFERENCES `grupos` (`nivel_grupo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
