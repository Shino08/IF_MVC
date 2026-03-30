-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS `instal_fuego`;
USE `instal_fuego`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- 1. TABLAS MAESTRAS (Sin dependencias ni ENUMs)
-- --------------------------------------------------------

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'admin'),
(2, 'cliente'),
(3, 'gerente_operaciones');

CREATE TABLE `metodos_de_pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `metodo` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `icono` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categorias` (`id`, `nombre`, `icono`) VALUES
(2, 'Extintores', NULL),
(3, 'Servicios de Mantenimiento', NULL);

CREATE TABLE `etiquetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_etiqueta` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_etiqueta` (`nombre_etiqueta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tipos_cobro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tipos_cobro` (`id`, `nombre`) VALUES 
(1, 'Por hora'), 
(2, 'Por unidad'), 
(3, 'Por metro lineal'), 
(4, 'Por proyecto');

CREATE TABLE `estados_cotizacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `estados_cotizacion` (`id`, `nombre`) VALUES
(1, 'borrador'),
(2, 'pendiente_revision'),
(3, 'enviada'),
(4, 'aprobada'),
(5, 'rechazada'),
(6, 'vencida');

-- --------------------------------------------------------
-- 2. USUARIOS Y CATÁLOGO (Productos y Servicios)
-- --------------------------------------------------------

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `empresa` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol_id` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `empresa`, `telefono`, `cedula`, `email`, `contrasena`, `rol_id`) VALUES
(2, 'Deiber', 'Vasquez', 'IF', '04244572397', 'V-30947692', 'deiberjvc@gmail.com', '$2y$10$ptUA6xe7uIT5v8s5qmAGZexKKjSUyn7gSpWOAG7YxLOWNEUe/leWu', 2),
(3, 'Admin', 'Admin', 'IF', '04244572397', 'V-12345678', 'admin@correo.com', '$2y$10$DIRAMGmhm3uf3DUeBLEeLO4qfP07qV/vyRYQXiSjOTu.FjCGg0H/C', 1);

CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) NOT NULL COMMENT 'Código único de inventario',
  `nombre` varchar(255) NOT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `certificaciones` varchar(255) DEFAULT NULL,
  `existencia` int(11) DEFAULT NULL,
  `dimensiones` varchar(255) DEFAULT NULL,
  `ficha_tecnica_pdf` varchar(255) DEFAULT NULL,
  `info_garantia` text DEFAULT NULL,
  `info_envio` text DEFAULT NULL,
  `estado_disponibilidad` varchar(100) DEFAULT NULL,
  `cantidad_minima_pedido` int(11) DEFAULT NULL,
  `imagen_principal` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku` (`sku`),
  CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `productos` (`id`, `sku`, `nombre`, `modelo`, `descripcion`, `categoria_id`, `precio`, `marca`, `existencia`, `imagen_principal`) VALUES
(1, 'COD-001', 'Extintor', 'AIR', 'Chamo esto es una descripcion de un extintor es rojo y se usa para apagar el fuego', 2, 20.00, 'Nike', 20, 'COD-001_1772142266_0.png');

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL COMMENT 'Ej: SRV-001',
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `precio_referencial` decimal(10,2) DEFAULT NULL,
  `tipo_cobro_id` int(11) DEFAULT NULL COMMENT 'Relacion con la tabla tipos_cobro',
  `imagen_principal` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  CONSTRAINT `fk_servicios_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_servicios_tipo_cobro` FOREIGN KEY (`tipo_cobro_id`) REFERENCES `tipos_cobro` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `servicios` (`id`, `codigo`, `nombre`, `descripcion`, `categoria_id`, `precio_referencial`, `tipo_cobro_id`) VALUES
(1, 'SRV-001', 'Recarga de Extintor PQS 10 Lbs', 'Servicio de vaciado, limpieza, recarga de polvo químico seco y presurización.', 3, 15.00, 2),
(2, 'SRV-002', 'Mano de Obra - Instalación de Panel', 'Instalación y configuración de panel de detección de humo. No incluye materiales.', 3, 25.00, 1);

-- --------------------------------------------------------
-- 3. TABLAS DE RELACIÓN (Imágenes y Etiquetas)
-- --------------------------------------------------------

CREATE TABLE `producto_etiquetas` (
  `producto_id` int(11) NOT NULL,
  `etiqueta_id` int(11) NOT NULL,
  PRIMARY KEY (`producto_id`,`etiqueta_id`),
  CONSTRAINT `fk_producto_etiquetas_etiqueta` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_producto_etiquetas_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `producto_imagenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_prod_imagenes_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `producto_imagenes` (`id`, `producto_id`, `ruta_imagen`) VALUES
(4, 1, 'COD-001_1772142605_0.png');

-- --------------------------------------------------------
-- 4. FLUJO DE COTIZACIONES
-- --------------------------------------------------------

CREATE TABLE `cotizaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_vencimiento` date DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `impuestos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado_id` int(11) NOT NULL DEFAULT 1 COMMENT '1 = borrador',
  `id_metodo_pago` int(11) DEFAULT NULL,
  `proyecto_referencia` varchar(255) DEFAULT NULL,
  `notas_tecnicas` text DEFAULT NULL,
  `direccion_envio` text DEFAULT NULL,
  `direccion_facturacion` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cotizaciones_estado` FOREIGN KEY (`estado_id`) REFERENCES `estados_cotizacion` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_cotizaciones_metodo_pago` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_de_pagos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_cotizaciones_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cotizacion_detalles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cotizacion_id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL COMMENT 'NULL si es un servicio',
  `servicio_id` int(11) DEFAULT NULL COMMENT 'NULL si es un producto',
  `cantidad` decimal(10,2) NOT NULL COMMENT 'Decimal para aceptar ej: 1.5 horas',
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_detalle_cotizacion` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_detalle_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `chk_item_tipo` CHECK ((`producto_id` IS NOT NULL AND `servicio_id` IS NULL) OR (`producto_id` IS NULL AND `servicio_id` IS NOT NULL))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reportes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_reporte` varchar(255) NOT NULL,
  `fecha_generacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `generado_por_usuario_id` int(11) DEFAULT NULL,
  `parametros` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parametros`)),
  `url_archivo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_reportes_usuario` FOREIGN KEY (`generado_por_usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;