================================================================================
MANUAL TECNICO — Plataforma Híbrida de Presupuestos y Pedidos InstalFuego (IF_MVC)
================================================================================

1. INFORMACION GENERAL
--------------------------------------------------------------------------------
- Proyecto: Plataforma Híbrida de Presupuestos y Pedidos para InstalFuego C.A.
- Entidad: InstalFuego C.A. — Sistemas de Seguridad Contra Incendios
- Version: 1.0 (Modulo de Cotizaciones + Catalogo + Admin)
- Arquitectura: MVC (Modelo-Vista-Controlador) personalizado con Front Controller
- Frontend: HTML5, CSS3 con Tailwind CSS v3 (compilado con Tailwind CLI via pnpm),
            JavaScript vanilla (AJAX, modales, fetch API)
- Backend: PHP 8.2 con autoloader PSR-4 propio
- Base de datos: MariaDB/MySQL con PDO
- Dependencias: Dompdf (generacion de PDF), PHPMailer (envio de correos)


2. ESTRUCTURA DEL DIRECTORIO
--------------------------------------------------------------------------------
IF_MVC/
  .htaccess                      - Rewrite rules y seguridad
  composer.json                  - Dependencias de Composer (Dompdf, PHPMailer)
  package.json                   - Dependencias de Node (Tailwind CSS)
  pnpm-lock.yaml                 - Lockfile de pnpm
  pnpm-workspace.yaml            - Archivo de workspace de pnpm
  autoload.php                   - Autocargador PSR-4 de namespaces

  public/                        - Document root (punto de entrada)
    index.php                    - Front Controller: session_start, routing dispatch
    .htaccess                    - RewriteRule a index.php
    css/
      input.css                  - Fuente Tailwind (directivas @tailwind)
      output.css                 - CSS compilado por Tailwind CLI
      styles.css                 - Estilos personalizados adicionales
    js/                          - JavaScript por modulo
    img/                         - Imagenes del sistema
      productos/                 - Imagenes de productos (SKU_timestamp_N.ext)
      servicios/                 - Imagenes de servicios

  app/
    Core/
      Router.php                 - Enrutador: registro GET/POST y dispatch con
                                   soporte de parametros {id} en URL
      Database.php               - Singleton de conexion PDO a MySQL
      Config.php                 - Constantes de configuracion (SMTP, etc.)
      PHPMailer/                 - Libreria PHPMailer para envio de correos

    Controllers/
      HomeController.php         - Pagina principal (landing) con destacados
      AuthController.php         - Login, registro, olvide/reset password
      DashboardController.php    - Panel admin: CRUD productos/servicios/
                                   categorias, gestion de cotizaciones, reportes
      ProductoController.php     - Crear, actualizar, eliminar, reemplazar
                                   imagenes de productos (API JSON)
      ServicioController.php     - CRUD de servicios con gestion de imagenes
      CategoriasController.php   - CRUD de categorias
      CatalogoController.php     - Catalogo publico (productos y servicios)
      CuentaController.php       - Perfil de usuario, seguridad
      CotizacionClienteController.php - Flujo cliente: borrador, envio,
                                   historial, detalle, PDF, envio por correo

    Models/
      ProductsModel.php          - CRUD productos, galeria de imagenes,
                                   busqueda por SKU
      ServiciosModel.php         - CRUD servicios, tipos de cobro
      CategoriasModel.php        - CRUD categorias
      UsersModel.php             - Autenticacion, registro, perfil, reset
                                   de password, session tokens
      CotizacionesModel.php      - Flujo completo de cotizaciones/presupuestos: borrador,
                                   items, envio, historial, admin, campos
                                   comerciales, calculo de totales
      PedidosModel.php           - Creación y actualización de logística y estados de pedidos
      PagosModel.php             - Registro, reporte y validación de pagos/comprobantes
      FacturasModel.php          - Generación e historial de facturas asociadas a pedidos

    Views/                       - Plantillas PHP organizadas por modulo
      layouts/                   - Layouts reutilizables (_head, _sidebar,
                                   header, footer, cuenta-sidebar)
      home.php                   - Landing page
      auth/                      - Login, registro, olvide/reset password
      dashboard/                 - Panel admin: index, productos, servicios,
                                   categorias, cotizaciones, reportes
      catalogo/                  - Catalogo publico: listado, detalle
                                   producto, detalle servicio
      cotizacion/                - Flujo cliente: actual, exito, historial,
                                   detalle, pdf_template
      cuenta/                    - Perfil, seguridad del usuario

    Db/
      instal_fuego.sql           - Esquema completo de BD con datos iniciales
                                   (seed) e histórico de tablas. Ubicado bajo app/Db/


3. BASE DE DATOS
--------------------------------------------------------------------------------
Motor: MariaDB/MySQL
Base de datos: instal_fuego
Juego de caracteres: utf8mb4_unicode_ci

Tablas principales:

  roles (id, nombre)
    - 1: admin, 2: cliente, 3: gerente_operaciones

  estados_cotizacion (id, nombre)
    - 1: borrador, 2: pendiente_revision, 3: enviada,
      4: aprobada, 5: rechazada, 6: vencida

  usuarios (id, nombre, apellido, empresa, telefono, cedula,
            email, contrasena, rol_id, session_token,
            fecha_creacion, fecha_actualizacion)
    - FK: rol_id -> roles.id (ON DELETE SET NULL)
    - UNIQUE: email

  categorias (id, nombre, icono)
    - UNIQUE: nombre

  productos (id, sku, nombre, modelo, descripcion, categoria_id,
             precio, marca, certificaciones, existencia, dimensiones,
             ficha_tecnica_pdf, info_garantia, info_envio,
             estado_disponibilidad, cantidad_minima_pedido,
             imagen_principal)
    - FK: categoria_id -> categorias.id (ON DELETE SET NULL)
    - UNIQUE: sku

  servicios (id, codigo, nombre, descripcion, categoria_id,
             precio_referencial, tipo_cobro_id, imagen_principal)
    - FK: categoria_id -> categorias.id
    - FK: tipo_cobro_id -> tipos_cobro.id
    - UNIQUE: codigo

  tipos_cobro (id, nombre)
    - 1: Por hora, 2: Por unidad, 3: Por metro lineal, 4: Por proyecto

  metodos_de_pagos (id, metodo)

  pedidos (id, cotizacion_id, usuario_id, fecha_creacion, total, costo_envio,
           id_metodo_pago, estado_pedido, direccion_envio, direccion_facturacion,
           tipo_entrega, referencia_pago, fecha_pago_reportado, fecha_pago_validado,
           fecha_despacho, fecha_entrega)
    - FK: cotizacion_id -> cotizaciones.id
    - FK: usuario_id -> usuarios.id
    - FK: id_metodo_pago -> metodos_de_pagos.id
    - estado_pedido: ENUM('pendiente_pago', 'pago_por_validar', 'procesando', 'despachado', 'entregado', 'cancelado')
    - fecha_despacho y fecha_entrega: campos DATETIME para registrar la transicion logistica de despacho y entrega formal.
    - tipo_entrega: ENUM('domicilio', 'retiro_tienda')

  cotizaciones (id, usuario_id, fecha_solicitud, fecha_vencimiento,
                total, subtotal, impuestos, descuento, estado_id,
                id_metodo_pago, proyecto_referencia, notas_tecnicas,
                notas_internas, condiciones_pago,
                direccion_envio, direccion_facturacion)
    - FK: usuario_id -> usuarios.id
    - FK: estado_id -> estados_cotizacion.id
    - FK: id_metodo_pago -> metodos_de_pagos.id
    - subtotal columna calculada: cantidad * precio_unitario (generated)

  cotizacion_detalles (id, cotizacion_id, producto_id, servicio_id,
                       cantidad, precio_unitario, subtotal)
    - FK: cotizacion_id -> cotizaciones.id (ON DELETE CASCADE)
    - FK: producto_id -> productos.id (ON DELETE RESTRICT)
    - FK: servicio_id -> servicios.id (ON DELETE RESTRICT)
    - CHECK: solo producto_id O servicio_id (no ambos)

  cotizacion_detalles.subtotal - Columna GENERADA ALWAYS AS
                                 (cantidad * precio_unitario) STORED

  reportes (id, tipo_reporte, fecha_generacion,
            generado_por_usuario_id, parametros JSON, url_archivo)
    - FK: generado_por_usuario_id -> usuarios.id


4. COMPONENTES DEL SISTEMA
--------------------------------------------------------------------------------

4.1 Enrutamiento (Front Controller)
  - public/index.php: unico punto de entrada, session_start, validacion de
    session_token, registro de rutas y dispatch
  - Router.php: registro estatico de rutas GET/POST con soporte de
    parametros {id} en URL, matching por expresion regular
  - Todas las rutas se definen en public/index.php usando:
    Router::get('/ruta', [Controller::class, 'metodo']);
    Router::post('/ruta', [Controller::class, 'metodo']);

4.2 Controladores
  HomeController        - Landing page con productos destacados
  AuthController        - Login, logout, registro, olvide password, reset password
  DashboardController   - Panel admin: dashboard KPI, CRUD productos/categorias/
                          servicios, gestion de cotizaciones (listado, detalle,
                          edicion comercial, emitir, rechazar), reportes
  ProductoController    - API JSON: crear, actualizar, eliminar productos,
                          gestion de imagenes (subir, borrar, reemplazar)
  ServicioController    - API JSON: CRUD servicios, gestion de imagenes
  CategoriasController  - API JSON: crear, eliminar categorias
  CatalogoController    - Catalogo publico: listado, detalle de producto y servicio
  CuentaController      - Perfil y seguridad del usuario
  CotizacionClienteController - Flujo cliente: borrador actual, agregar items,
                          actualizar/eliminar items, enviar solicitud, historial,
                          detalle, generacion PDF, envio por correo

4.3 Modelos
  ProductsModel     - CRUD completo con transacciones, busqueda por SKU,
                      galeria de imagenes (principal + secundarias),
                      soft validation de duplicados
  ServiciosModel    - CRUD con tipos de cobro, gestion de imagen principal
  CategoriasModel   - CRUD basico
  UsersModel        - Autenticacion con password_hash/verify, session_token,
                      registro con validacion de duplicados email/cedula,
                      reset de password con tokens expirables
  CotizacionesModel - Flujo completo de cotización/presupuesto borrador por usuario,
                      ítems, envío de solicitud, historial y edición comercial.
  PedidosModel      - Gestiona la conversión de cotización a pedido real (compras directas),
                      el almacenamiento del tipo de entrega y dirección consolidada, 
                      y la actualización del estado logístico del pedido.
  PagosModel        - Gestiona el reporte de pagos por parte del cliente, asocia comprobantes
                      y referencias bancarias, y permite la validación o rechazo del pago.
  FacturasModel     - Crea y administra las facturas generadas a partir de pedidos aceptados.


5. SISTEMA DE RUTAS
--------------------------------------------------------------------------------
Todas las rutas se definen en public/index.php con sintaxis:

  Router::get('/ruta/{param}', [Namespace\\Controller::class, 'metodo']);
  Router::post('/ruta/{param}', [Namespace\\Controller::class, 'metodo']);

El Router::dispatch() compara la URI contra patrones convertidos a regex
y ejecuta el controlador con los parametros extraidos.

Soporta parametros en URL con sintaxis {id} -> ([a-zA-Z0-9_-]+).

Rutas principales:
  /                                      - Landing page
  /login, /register, /olvide-password,
  /reset-password                        - Autenticacion
  /dashboard[/...]                       - Panel admin
  /catalogo, /producto/{id},
  /servicio/{id}                         - Catalogo publico
  /pedido/actual, /pedido/agregar,
  /pedido/enviar, /mis-pedidos,
  /mis-pedidos/{id}, /pedido/pagar/{id}  - Flujo cliente (Pedidos/Checkout)
  /cotizacion/pdf/{id}                   - PDF Pedido
  /factura/pdf/{id}                      - PDF Factura
  /cuenta, /cuenta/perfil,
  /cuenta/seguridad                      - Perfil de usuario


6. FLUJO DE COMPRA Y PEDIDOS
--------------------------------------------------------------------------------

  1. CLIENTE: Navega el catálogo, agrega ítems a su carrito
     - GET /pedido/actual (Carrito de compra)
     - POST /pedido/agregar (producto_id o servicio_id + cantidad)
     - POST /pedido/item/actualizar (detalle_id + cantidad)
     - POST /pedido/item/eliminar (detalle_id)

  2. CLIENTE: Procesa el Checkout
     - Selecciona Método de Entrega: "Retiro en Tienda" o "Envío a Domicilio".
     - Para Envío a Domicilio, rellena campos estructurados: Estado, Municipio, Dirección Completa y Punto de Referencia.
     - Confirma el pedido mediante POST /pedido/enviar.
     - Estado inicial del pedido: pendiente_pago.

  3. CLIENTE: Reporta el Pago (POST /pedido/pagar/{id})
     - Si paga en efectivo/divisas de forma presencial, se omite el comprobante y referencia.
     - Si paga por transferencia/pago móvil, adjunta referencia y capture de pantalla.
     - Estado del pedido: pendiente_pago -> pago_por_validar.

  4. ADMIN: Valida o rechaza el pago
     - Desde el Dashboard, valida el pago reportado.
     - Si se valida: estado_pedido cambia a "procesando" y la orden pasa a preparación.
     - Si se rechaza: regresa a "pendiente_pago" para que el cliente lo re-envíe.

  5. ADMIN: Despacha el pedido (Panel de Despacho)
     - El vendedor avanza el estado del pedido:
       * "procesando" -> "despachado" (se registra fecha_despacho).
       * "despachado" -> "entregado" (se registra fecha_entrega).
       * Opcionalmente, se puede cancelar el pedido (estado: "cancelado").

  6. CLIENTE: Seguimiento en tiempo real (Timeline)
     - El cliente ve un timeline de 5 pasos en el detalle de su pedido:
       Recibido -> Pago Reportado -> Pago Validado -> Preparando/En Camino (según método de entrega) -> Entregado.
     - Muestra fechas exactas de confirmación de pago y despacho.

  7. GENERACION DE PDF: Dompdf con template en app/Views/cotizacion/pdf_template.php (imprime como Pedido en lugar de Cotización).


7. SEGURIDAD IMPLEMENTADA
--------------------------------------------------------------------------------
- password_hash(PASSWORD_DEFAULT) + password_verify() para contrasenas
- Session token (bin2hex(random_bytes(32))) validado en cada request
- Verificacion de sesion en cada controlador (isset($_SESSION['user_id']))
- Control de acceso por rol en DashboardController::requireAuth()
  (solo admin=1 y gerente_operaciones=3)
- htmlspecialchars() en todas las vistas para prevenir XSS
- strip_tags() en todas las entradas de texto en controladores
- Consultas parametrizadas (PDO prepared statements) en todos los modelos
- Whitelist de campos permitidos en updateComercialFields()
- Validacion de SKU unico antes de insertar/actualizar productos
- Whitelist de extensiones de imagen (jpg, jpeg, png, webp)
- Limite de 5 imagenes por producto
- Soft validation de SQL keywords en campos de texto (via strip_tags)


8. GENERACION DE PDF (Dompdf)
--------------------------------------------------------------------------------
 Libreria: Dompdf (instalada via Composer)

 Flujo:
  1. CotizacionClienteController::generatePdfContent() obtiene datos de la
     cotizacion y los detalles
  2. Renderiza el template app/Views/cotizacion/pdf_template.php en buffer
  3. Dompdf convierte el HTML a PDF con opcion isRemoteEnabled=true
  4. Se puede descargar inline o como attachment

 El template incluye:
  - Encabezado con datos de la empresa (InstalFuego C.A.)
  - Datos del cliente
  - Tabla de items con descripcion, tipo, cantidad, precio unitario, subtotal
  - Calculos: subtotal, IVA (16%), descuento, total USD
  - Notas tecnicas/comerciales
  - Terminos y condiciones

9. INTERFAZ DE USUARIO (Frontend)
--------------------------------------------------------------------------------
- Framework CSS: Tailwind CSS v3 compilado localmente via pnpm/tailwindcss CLI
  (fichero input.css -> output.css)
- Sin framework JS externo: JavaScript vanilla con fetch API para AJAX
- Modales personalizados para notificaciones y confirmaciones
- Wizard de 3 pasos para la gestion admin de cotizaciones
- Diseño responsive con sidebar fijo y layout de 2-3 columnas
- Vista previa tipo factura en el paso de revision


10. INSTALACION Y DESPLIEGUE
--------------------------------------------------------------------------------
Requisitos:
  - PHP 8.2+ con extensiones pdo_mysql, mbstring, fileinfo
  - MySQL/MariaDB
  - Node.js 18+ y pnpm (para compilar Tailwind)
  - Composer (para dependencias PHP)

Pasos:
  1. Clonar el repositorio en el document root del servidor web
  2. Ejecutar: composer install
  3. Ejecutar: pnpm install
  4. Compilar Tailwind: pnpm exec tailwindcss -i public/css/input.css
     -o public/css/output.css
  5. Importar Db/instal_fuego.sql en MySQL
  6. Configurar credenciales en app/Core/Database.php
     (host, dbname, user, pass)
  7. Configurar credenciales SMTP en app/Core/Config.php (opcional)
  8. Acceder via navegador a la URL del proyecto
  9. Usuario admin por defecto: admin@correo.com / password: (ver seed SQL)

Configuracion del servidor web:
  - Apache: .htaccess incluido para mod_rewrite
  - Document root debe apuntar a /public/
  - PHP requiere extension pdo_mysql habilitada

================================================================================
Fin del Manual Tecnico
