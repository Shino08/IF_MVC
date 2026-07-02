-- Creación de la base de datos
CREATE DATABASE IF NOT EXISTS `instal_fuego`;
USE `instal_fuego`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

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

INSERT INTO `metodos_de_pagos` (`metodo`) VALUES
('Pago Móvil'),
('Transferencia Bancaria'),
('Efectivo'),
('Divisas');

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
(4, 'facturado'),
(1, 'borrador'),
(3, 'listo_para_pago'),
(2, 'pendiente_revision'),
(5, 'anulado'),
(6, 'cancelado');

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
  `session_token` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `empresa`, `telefono`, `cedula`, `email`, `contrasena`, `rol_id`) VALUES
(2, 'Deiber', 'Vasquez', 'IF', '04244572397', 'V-30947692', 'deiberjvc@gmail.com', '$2y$10$HT7w1/E.JA9MYp5Ge4RKC.AIyq2vnm2Dr3EovZpJGjw/5kChEr3Q6', 2),
(3, 'Admin', 'Admin', 'IF', '04244572397', 'V-12345678', 'admin@correo.com', '$2y$10$HT7w1/E.JA9MYp5Ge4RKC.AIyq2vnm2Dr3EovZpJGjw/5kChEr3Q6', 1);

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
  `descuento` decimal(10,2) NOT NULL DEFAULT 0.00,
  `costo_envio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado_id` int(11) NOT NULL DEFAULT 1 COMMENT '1 = borrador',
  `proyecto_referencia` varchar(255) DEFAULT NULL,
  `condiciones_pago` text DEFAULT NULL,
  `notas_internas` text DEFAULT NULL,
  `notas_tecnicas` text DEFAULT NULL,
  `tipo_entrega` enum('domicilio','retiro_tienda') DEFAULT NULL,
  `direccion_envio` text DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `fecha_tentativa` date DEFAULT NULL,
  `responsable_nombre` varchar(255) DEFAULT NULL,
  `responsable_telefono` varchar(50) DEFAULT NULL,
  `observaciones_tecnicas` text DEFAULT NULL,
  `estado_logistico` enum('pendiente','en_proceso','completado') DEFAULT 'pendiente',
  `tasabcv` decimal(10,4) DEFAULT NULL,
  `montousd` decimal(10,2) DEFAULT NULL,
  `tipo_flujo` enum('presupuesto','compra_directa') NOT NULL DEFAULT 'presupuesto',
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_cotizaciones_estado` FOREIGN KEY (`estado_id`) REFERENCES `estados_cotizacion` (`id`) ON DELETE RESTRICT,
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
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cotizacion_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `costo_envio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `id_metodo_pago` int(11) DEFAULT NULL,
  `estado_pedido` enum('pendiente_pago','pago_por_validar','procesando','despachado','entregado','cancelado') NOT NULL DEFAULT 'pendiente_pago',
  `direccion_envio` text DEFAULT NULL,
  `direccion_facturacion` text DEFAULT NULL,
  `tipo_entrega` enum('domicilio','retiro_tienda') DEFAULT NULL,
  `referencia_pago` varchar(255) DEFAULT NULL,
  `fecha_pago_reportado` datetime DEFAULT NULL,
  `fecha_pago_validado` datetime DEFAULT NULL,
  `fecha_despacho` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pedido_cotizacion` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_pedido_metodo_pago` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_de_pagos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `metodo_pago_id` int(11) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `moneda` varchar(10) DEFAULT 'VES',
  `referencia` varchar(255) NOT NULL,
  `banco_origen` varchar(255) DEFAULT NULL,
  `telefono_pagador` varchar(50) DEFAULT NULL,
  `cedula_pagador` varchar(50) DEFAULT NULL,
  `comprobante_url` varchar(255) DEFAULT NULL,
  `estado` enum('por_validar','validado','rechazado') NOT NULL DEFAULT 'por_validar',
  `observaciones_admin` text DEFAULT NULL,
  `fecha_reporte` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_validacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pagos_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_pagos_metodo` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_de_pagos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `numero_factura` varchar(50) NOT NULL,
  `fecha_emision` datetime NOT NULL DEFAULT current_timestamp(),
  `subtotal` decimal(10,2) NOT NULL,
  `impuestos` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL,
  `costo_envio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL,
  `cliente_nombre` varchar(255) DEFAULT NULL,
  `cliente_cedula` varchar(50) DEFAULT NULL,
  `cliente_direccion` text DEFAULT NULL,
  `cliente_email` varchar(255) DEFAULT NULL,
  `metodo_pago_texto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_factura` (`numero_factura`),
  CONSTRAINT `fk_facturas_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE RESTRICT
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

CREATE TABLE `password_resets` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuarioid` INT NOT NULL,
  `token_hash` VARCHAR(255) NOT NULL,
  `expiracion` DATETIME NOT NULL,
  `usado` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_password_resets_usuario` FOREIGN KEY (`usuarioid`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
CREATE TABLE `servicio_imagenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `servicio_id` int(11) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_srv_imagenes_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
