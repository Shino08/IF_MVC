-- phpMyAdmin SQL Dump
-- Sistema de Cotizaciones B2B - Seguridad Contra Incendios
-- Optimizado para Ingeniería y Producción Industrial

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `instal_fuego_v2.0_UNEFA`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `icono` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `etiquetas`
-- (Útil para clasificar: "Uso Interior", "Alta Presión", "Riesgo Eléctrico")
--

CREATE TABLE `etiquetas` (
  `id` int(11) NOT NULL,
  `nombre_etiqueta` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
-- (Limpiada de E-commerce, enfocada en Fichas Técnicas)
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL COMMENT 'Código único de inventario',
  `nombre` varchar(255) NOT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL COMMENT 'Precio base referencial',
  `marca` varchar(255) DEFAULT NULL,
  `certificaciones` varchar(255) DEFAULT NULL COMMENT 'Ej: UL, FM, NFPA, CE',
  `existencia` int(11) DEFAULT NULL,
  `dimensiones` varchar(255) DEFAULT NULL,
  `ficha_tecnica_pdf` varchar(255) DEFAULT NULL COMMENT 'URL al archivo PDF',
  `info_garantia` text DEFAULT NULL,
  `info_envio` text DEFAULT NULL,
  `estado_disponibilidad` varchar(100) DEFAULT NULL,
  `cantidad_minima_pedido` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_etiquetas`
--

CREATE TABLE `producto_etiquetas` (
  `producto_id` int(11) NOT NULL,
  `etiqueta_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_de_pagos`
-- (En B2B suele ser: "Transferencia 30 días", "Cheque conformable", etc.)
--

CREATE TABLE `metodos_de_pagos` (
  `id` int(11) NOT NULL,
  `metodo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL COMMENT 'Ej: admin, ingeniero_ventas, cliente_b2b'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
-- (Adaptada para clientes empresariales)
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `empresa` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones`
-- (Reemplaza a la antigua tabla facturas)
--

CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_vencimiento` date DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `impuestos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` enum('borrador','pendiente_revision','enviada','aprobada','rechazada','vencida') DEFAULT 'borrador',
  `id_metodo_pago` int(11) DEFAULT NULL,
  `proyecto_referencia` varchar(255) DEFAULT NULL COMMENT 'Nombre de la obra o planta',
  `notas_tecnicas` text DEFAULT NULL COMMENT 'Especificaciones del cliente',
  `direccion_envio` text DEFAULT NULL,
  `direccion_facturacion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_detalles`
-- (El corazón del sistema B2B: guarda los ítems de cada presupuesto)
--

CREATE TABLE `cotizacion_detalles` (
  `id` int(11) NOT NULL,
  `cotizacion_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL COMMENT 'Congela el precio al momento de cotizar',
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id` int(11) NOT NULL,
  `tipo_reporte` varchar(255) NOT NULL COMMENT 'Ej: Cotizaciones Mensuales, Proyectos Aprobados',
  `fecha_generacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `generado_por_usuario_id` int(11) DEFAULT NULL,
  `parametros` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parametros`)),
  `url_archivo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

ALTER TABLE `cotizaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cotizaciones_usuario` (`usuario_id`),
  ADD KEY `fk_cotizaciones_metodo_pago` (`id_metodo_pago`);

ALTER TABLE `cotizacion_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_cotizacion` (`cotizacion_id`),
  ADD KEY `fk_detalle_producto` (`producto_id`);

ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_etiqueta` (`nombre_etiqueta`);

ALTER TABLE `metodos_de_pagos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_productos_categoria` (`categoria_id`);

ALTER TABLE `producto_etiquetas`
  ADD PRIMARY KEY (`producto_id`,`etiqueta_id`),
  ADD KEY `fk_producto_etiquetas_etiqueta` (`etiqueta_id`);

ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reportes_usuario` (`generado_por_usuario_id`);

ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_usuarios_rol` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

ALTER TABLE `categorias` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `cotizaciones` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `cotizacion_detalles` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `etiquetas` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `metodos_de_pagos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `productos` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `reportes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `roles` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `usuarios` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

ALTER TABLE `cotizaciones`
  ADD CONSTRAINT `fk_cotizaciones_metodo_pago` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_de_pagos` (`id`),
  ADD CONSTRAINT `fk_cotizaciones_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

ALTER TABLE `cotizacion_detalles`
  ADD CONSTRAINT `fk_detalle_cotizacion` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

ALTER TABLE `producto_etiquetas`
  ADD CONSTRAINT `fk_producto_etiquetas_etiqueta` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_producto_etiquetas_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

ALTER TABLE `reportes`
  ADD CONSTRAINT `fk_reportes_usuario` FOREIGN KEY (`generado_por_usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;