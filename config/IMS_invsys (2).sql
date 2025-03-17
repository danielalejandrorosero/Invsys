-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 16-03-2025 a las 17:10:39
-- Versión del servidor: 11.7.2-MariaDB
-- Versión de PHP: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `IMS_invsys`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`) VALUES
(1, 'Electrónica', 'Dispositivos electrónicos y accesorios'),
(2, 'Ropa', 'Prendas de vestir para todas las edades'),
(3, 'Hogar', 'Artículos para el hogar y decoración');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_usuarios`
--

CREATE TABLE `imagenes_usuarios` (
  `id_imagen` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_imagen` varchar(255) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `movimientos_stock`
--

INSERT INTO `movimientos_stock` (`id_movimiento`, `id_producto`, `id_almacen_origen`, `id_almacen_destino`, `tipo_movimiento`, `cantidad`, `fecha_movimiento`, `id_usuario`) VALUES
(1, 1, 2, 1, 'entrada', 50, '2025-03-12 22:05:59', 47),
(2, 2, 1, 2, 'entrada', 100, '2025-03-12 22:05:59', 44),
(3, 3, 1, 2, 'transferencia', 10, '2025-03-12 22:05:59', 47);

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
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `codigo`, `sku`, `descripcion`, `precio_compra`, `precio_venta`, `id_unidad_medida`, `stock_minimo`, `stock_maximo`, `id_categoria`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'Smartphone X', 'PROD001', 'SKU12345', 'Teléfono inteligente última generación', 800000.00, 1200000.00, 2, 5, 100, 1, '2025-03-12 22:05:59', '2025-03-15 16:36:30'),
(2, 'Camisa Algodón', 'PROD002', 'SKU54321', 'Camisa de algodón talla M', 50000.00, 85000.00, 1, 20, 200, 2, '2025-03-12 22:05:59', '2025-03-12 22:05:59'),
(3, 'Juego de Sábanas', 'PROD003', 'SKU67890', 'Sábanas de algodón egipcio', 120000.00, 180000.00, 1, 15, 150, 3, '2025-03-12 22:05:59', '2025-03-12 22:05:59'),
(7, 'ProductoPrueba', 'PROD999', 'SKU99999', 'Este es un producto de prueba', 1000.50, 1500.75, 1, 5, 50, 1, '2025-03-13 03:12:01', '2025-03-13 03:12:01'),
(12, 'ProductoPrueba', 'PROD9999', 'SKU999999', 'Este es un producto de prueba', 1000.50, 1500.75, 1, 5, 50, 1, '2025-03-13 03:20:41', '2025-03-13 03:20:41'),
(13, 'ProductoPrueba', 'PROD9999999', 'SKU9999999', 'Este es un producto de prueba', 1000.50, 1500.75, 1, 5, 50, 1, '2025-03-13 03:39:21', '2025-03-13 03:39:21'),
(14, 'ProductoActualizado', 'PROD000014', 'SKU000014', 'Este producto fue editado de prueba', 1200.50, 1700.75, 1, 5, 50, 1, '2025-03-13 19:59:54', '2025-03-13 20:07:33');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `stock_almacen`
--

INSERT INTO `stock_almacen` (`id_stock`, `id_almacen`, `id_producto`, `cantidad_disponible`) VALUES
(1, 1, 12, 1),
(2, 1, 3, 111),
(3, 2, 2, 111),
(4, 2, 3, 100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades_medida`
--

CREATE TABLE `unidades_medida` (
  `id_unidad` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `email`, `password`, `status`, `last_login`, `nivel_usuario`, `nombreUsuario`, `token_recuperacion`, `expira_token`) VALUES
(42, 'NudevoNombre', 'usuario_prueba@example.com', '$2y$12$1xlEgpJPsDDmigAxBK1VqurC8FDzMCHdjTOS44ej3fHLorYy8OjOi', 1, '2025-03-09 23:26:57', 1, 'nduevo_usuario', NULL, NULL),
(44, 'ddadwqdwqddni', 'usuario_nuevo@example.com', '$2y$12$C5FqLc/lNPSW8uC0YtozcuQL/QOY/fkYIC8nqrXM7d/YwA0QY3K6W', 1, '2025-03-09 23:39:28', 1, 'dwdwdadndiel', NULL, NULL),
(45, 'UsuarioNuevo', 'udsuadwqdrio_nuevo@example.com', '$2y$12$1nBRJCz2LmZbh3W3aeJvl.kn7pS8hHLerONGFhun0BWcjBa82lkou', 1, '2025-03-09 23:41:40', 1, 'usduario_nuevo', NULL, NULL),
(46, 'UsuarioNuevo', 'uddsuadwqdrio_nuevo@example.com', '$2y$12$rsyMG/ne7vtXTV2Tf6gLGeyqNDr2cxhQekzMERzrdERnnOOXiE87G', 1, '2025-03-10 04:24:18', 1, 'udddddsduario_nuevo', NULL, NULL),
(47, 'daniel alejandro', 'danielalejandroroseroortiz80@gmail.com', '$2y$12$2vEg5sPHM9WGW.p6zBa0yuB2OioDBDPtf2RziHG25Gke3ptVY.ToG', 1, '2025-03-11 02:54:17', 1, 'danielalejandrorosero', NULL, NULL),
(53, 'daniel alejandro', 'danielalejandroroseroddortiz80@gmail.com', '$2y$12$LLOeUvPVCMKOSiLGsbjlfOFVs1f/0dJZZTn4GT0Xp50oAn0SjwA3S', 1, '2025-03-11 19:19:58', 1, 'admin', NULL, NULL),
(54, 'Usuario Prueba', 'test@example.com', '$2y$12$1xlEgpJPsDDmigAxBK1VqurC8FDzMCHdjTOS44ej3fHLorYy8OjOi', 1, NULL, 2, 'test_user', NULL, NULL),
(55, 'daniel alejandro', 'danieladdwqdlejandroroseroortiz80@gmail.com', '$2y$12$t1EXcGjI1rNPTRulqnCPceQwAuUpcPK4ZO8zTFG0LCDwnOH7WTY56', 1, '2025-03-12 19:14:55', 1, 'adminn', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `fecha_venta` timestamp NULL DEFAULT current_timestamp(),
  `total` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  ADD KEY `fk_unidad_medida` (`id_unidad_medida`);

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
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `imagenes_usuarios`
--
ALTER TABLE `imagenes_usuarios`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos_stock`
--
ALTER TABLE `movimientos_stock`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `stock_almacen`
--
ALTER TABLE `stock_almacen`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `unidades_medida`
--
ALTER TABLE `unidades_medida`
  MODIFY `id_unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

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
  ADD CONSTRAINT `alertas_stock_ibfk_2` FOREIGN KEY (`id_almacen`) REFERENCES `stock_almacen` (`id_almacen`);

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
  ADD CONSTRAINT `fk_movimientos_stock_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `movimientos_stock_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`),
  ADD CONSTRAINT `movimientos_stock_ibfk_2` FOREIGN KEY (`id_almacen_origen`) REFERENCES `almacenes` (`id_almacen`),
  ADD CONSTRAINT `movimientos_stock_ibfk_3` FOREIGN KEY (`id_almacen_destino`) REFERENCES `almacenes` (`id_almacen`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
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
