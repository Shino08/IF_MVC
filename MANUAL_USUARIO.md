================================================================================
MANUAL DE USUARIO — Plataforma Híbrida de Presupuestos y Pedidos InstalFuego (IF_MVC)
================================================================================

1. REQUISITOS PARA ENTRAR AL SISTEMA
--------------------------------------------------------------------------------
- Tener una cuenta registrada en el sistema como cliente.
- Acceder via navegador web a la URL donde esta alojado el sistema.
- Disponer de conexion al servidor (no requiere internet si es local).
- Ingresar credenciales: correo electronico y contrasena.

Si aun no tiene cuenta, haga clic en "Registrarse" en la pantalla de login.
Debera proporcionar:
  - Nombre completo
  - Apellido
  - Cedula / RIF
  - Empresa (opcional)
  - Telefono
  - Correo electronico
  - Contrasena segura

Si olvida su contrasena, use el enlace "¿Olvidaste tu contraseña?" en la
pantalla de login. Recibira un enlace de restablecimiento en su correo
electronico con validez de 60 minutos.


2. CATALOGO DE PRODUCTOS Y SERVICIOS
--------------------------------------------------------------------------------
El catalogo publico muestra todos los productos y servicios disponibles de
InstalFuego C.A. Se accede desde la pagina principal o directamente en la
URL /catalogo.

2.1 NAVEGACION DEL CATALOGO
    - La pagina de inicio muestra el catalogo completo con productos
      y servicios organizados en tarjetas.
    - Cada tarjeta muestra: imagen del producto/servicio, nombre,
      categoria, precio referencial y codigo/SKU.
    - Haga clic en una tarjeta para ver el detalle completo del producto
      o servicio.

2.2 DETALLE DE PRODUCTO
    Al hacer clic en un producto, se muestra:
    - Imagen principal del producto
    - Nombre, SKU, modelo y marca
    - Descripcion tecnica
    - Precio de referencia
    - Categoria a la que pertenece
    - Stock disponible
    - Informacion de garantia y envio (si aplica)
    - Galeria de imagenes adicionales
    - Boton "Agregar" (sustituyendo el antiguo "Cotizar")

2.3 DETALLE DE SERVICIO
    Al hacer clic en un servicio, se muestra:
    - Imagen del servicio
    - Nombre, codigo
    - Descripcion del servicio
    - Precio referencial
    - Tipo de cobro (Por hora, Por unidad, Por metro lineal, Por proyecto)
    - Boton "Agregar" (sustituyendo el antiguo "Cotizar")


3. SOLICITUD DE PEDIDO
--------------------------------------------------------------------------------
El sistema permite armar una solicitud de pedido con productos y/o
servicios antes de enviarla al equipo de InstalFuego.

3.1 AGREGAR ITEMS AL CARRITO
    Desde el detalle de cualquier producto o servicio:
    1. Haga clic en "Agregar".
    2. En el modal, seleccione la cantidad deseada.
    3. Haga clic en "Agregar".
    4. El item se agregara a su carrito actual.

    Tambien puede ir directamente a "Carrito" en el menu
    para ver todos los items agregados.

3.2 GESTIONAR EL CARRITO
    En la pagina "Carrito" (/pedido/actual) puede:
    - Ver todos los items agregados con sus cantidades y precios
    - Modificar cantidades usando los botones +/-
    - Eliminar items individuales con el icono de papelera
    - Ver el subtotal actualizado en tiempo real

3.3 CONFIRMAR EL PEDIDO
    Cuando tenga todos los items deseados en su lista:
    - Seleccione el Método de Entrega (Retiro o Envío).
    - Para Envío a Domicilio, rellene los datos de dirección y referencia.
    - Opcionalmente agregue especificaciones adicionales en el cuadro de "Observaciones".
    - Haga clic en el botón "Confirmar Pedido".
    - El pedido se registrará y será redirigido a la pantalla de pago.

    NOTA: No puede confirmar un pedido vacío. Debe tener al menos
    un producto o servicio agregado.


4. HISTORIAL DE PEDIDOS
--------------------------------------------------------------------------------
En la seccion "Mis Pedidos" (/mis-pedidos) puede ver el
historial completo de todas sus solicitudes enviadas.

Cada entrada del historial muestra:
- Número de Pedido (#PED-AÑO-XXXX)
- Fecha del pedido
- Estado actual (Pendiente de Pago, Pago en Revisión, Preparando, En Camino, Entregado, Cancelado)
- Monto total en USD
- Enlace para ver el detalle completo

Los estados de un pedido son:
  - **Pendiente de Pago:** Pedido confirmado, esperando que reporte su pago.
  - **Pago en Revisión:** Reporte de pago recibido y bajo análisis de administración.
  - **Preparando Pedido:** Pago aprobado; el personal alista sus productos y logística.
  - **En Camino / Listo para Retiro:** En despacho de transporte (domicilio) o listo en la sede de la tienda (retiro).
  - **Entregado:** Recibido formalmente por el cliente.
  - **Cancelado:** Pedido anulado.


5. DETALLE Y TIMELINE DE SEGUIMIENTO
--------------------------------------------------------------------------------
Al acceder al detalle de cualquier pedido en su historial (/mis-pedidos/{id}), visualizará un panel dinámico de seguimiento:

5.1 TIMELINE VISUAL EN TIEMPO REAL
    Un indicador interactivo de 5 etapas le permite conocer la ubicación de su pedido:
    1. **Pedido Recibido:** Indica que la solicitud fue registrada.
    2. **Pago Reportado:** Se activa tras subir el capture del pago.
    3. **Pago Validado:** Confirmación de cobro por parte de administración.
    4. **Preparando / En Camino:** Informa si su paquete está siendo preparado o si ya está en tránsito hacia su dirección (o listo para retiro físico).
    5. **Entregado:** Culminación de la entrega.

    Cada etapa completada se ilumina en rojo/verde y muestra la fecha y hora exactas en que ocurrió el evento.

5.2 INFORMACIÓN LOGÍSTICA
    - Método de entrega acordado.
    - Dirección exacta desglosada y referencia provista.
    - Conversión de precios referenciales de USD a Bolívares usando la tasa oficial del BCV del día.


6. DESCARGA DE PDF
--------------------------------------------------------------------------------
Desde la pagina de detalle de cualquier pedido (tanto en el historial
como en la vista de cliente), puede descargar el documento en formato PDF.

Pasos:
  1. Abra el detalle del pedido.
  2. Haga clic en el boton "Descargar PDF".
  3. El PDF se abrira en una nueva pestana o se descargara
     automaticamente, dependiendo de su navegador.

El PDF incluye toda la informacion del documento: encabezado, datos del
cliente, items, totales, notas y terminos. Es apto para imprimir y
presentar como documento formal.


7. PANEL DE ADMINISTRACION (SOLO PERSONAL AUTORIZADO)
--------------------------------------------------------------------------------
El panel de administración está disponible solo para usuarios con rol de Administrador o Gerente de Operaciones. No es accesible para clientes.

7.1 DASHBOARD
    Página principal del panel que muestra:
    - KPIs: total de productos, servicios, categorías
    - Alertas de stock (productos sin existencia)
    - Gráfico de distribución de productos por categoría
    - Lista de servicios activos
    - Últimos productos agregados

7.2 GESTION DE PRODUCTOS Y SERVICIOS
    - CRUD completo de productos con gestión de SKU, marcas, stock y subida de hasta 5 imágenes por producto.
    - CRUD completo de servicios y asignación del tipo de cobro.
    - CRUD de categorías.

7.3 GESTION DE PEDIDOS Y VALIDACIÓN DE PAGO
    El administrador gestiona las solicitudes de compras mediante un flujo estructurado:
    1. **Revisión de Pedido (Detalle):** Permite ver la información del cliente, los productos solicitados con sus precios y la dirección de envío o almacén seleccionado.
    2. **Validación del Pago:** El administrador visualiza los detalles del comprobante subido por el cliente (referencia bancaria, captura de pantalla o modalidad de efectivo presencial) y tiene las opciones de:
       - **Validar:** Aprueba el pago, cambiando el estado del pedido a "Preparando Pedido" (procesando).
       - **Rechazar:** Rechaza el pago añadiendo un comentario para que el cliente vuelva a reportarlo correctamente.
    3. **Panel de Despacho (Logística):** Una vez que el pago es válido, se activa la sección de despacho donde el administrador puede:
       - Registrar el envío marcándolo como **Despachado** (se guarda automáticamente la fecha de despacho y se notifica en el timeline del cliente).
       - Confirmar la llegada final marcándolo como **Entregado** (se guarda la fecha de entrega y cierra el pedido).
       - Cancelar el pedido en caso de problemas de stock o logística.

7.4 REPORTES
    Generación de reportes con filtros:
    - Tipo de reporte: Ventas / Pedidos / Más Solicitados
    - Filtro por estado del pedido (Pendiente de Pago, Preparando, Despachado, Entregado, Cancelado)
    - Rango de fechas y exportación a formato CSV.


8. PERFIL DE USUARIO
--------------------------------------------------------------------------------
En la seccion "Mi Cuenta" (/cuenta) puede gestionar su informacion
personal.

8.1 MI PERFIL (/cuenta/perfil)
    Permite editar:
    - Nombre
    - Apellido
    - Cedula / RIF
    - Empresa
    - Telefono
    - Correo electronico

8.2 SEGURIDAD (/cuenta/seguridad)
    Permite cambiar la contrasena de acceso al sistema.

8.3 MIS PEDIDOS (/mis-pedidos)
    Acceso directo al historial completo de pedidos.


9. TIPOS DE USUARIO Y PERMISOS
--------------------------------------------------------------------------------
El sistema maneja tres roles con diferentes niveles de acceso:

  ADMIN (rol_id = 1)
  - Acceso completo al panel de administracion
  - Gestion de productos, servicios, categorias
  - Gestion de pedidos (revisar, validar pago, despachar)
  - Reportes y exportacion

  GERENTE DE OPERACIONES (rol_id = 3)
  - Acceso al panel de administracion
  - Gestion de pedidos (revisar, validar pago, despachar)
  - Reportes y exportacion
  - Gestion de servicios
  - SIN acceso a gestion de productos

  CLIENTE (rol_id = 2)
  - Catálogo de productos y servicios
  - Solicitud de Pedidos (añadir al carrito, procesar checkout)
  - Historial y seguimiento de Pedidos (visualización de timeline de entrega)
  - Perfil de usuario
  - SIN acceso al panel de administración


10. PREGUNTAS FRECUENTES
--------------------------------------------------------------------------------

10.1 ¿CÓMO REALIZO UN PEDIDO?
     Navegue por el catálogo de productos y servicios, haga clic en el botón "Agregar" para sumarlos a su Carrito de compra, vaya a su Carrito (/pedido/actual), seleccione su método de entrega (con su dirección si es despacho a domicilio) y pulse el botón "Confirmar Pedido".

10.2 ¿PUEDO MODIFICAR UN PEDIDO CONFIRMADO?
     No, una vez que el pedido es confirmado en el sistema no puede modificarse directamente desde la web. Si requiere algún cambio en la orden o en los datos de entrega, póngase en contacto inmediato con un asesor de InstalFuego.

10.3 ¿QUÉ DEBO HACER TRAS CONFIRMAR MI PEDIDO?
     Debe proceder a reportar su pago desde el botón "Reportar Pago" en el detalle de su pedido. Si el pago es en efectivo de forma presencial al recibirlo, simplemente marque la casilla correspondiente para que el sistema lo registre.

10.4 ¿CÓMO SEGUIR EL ESTADO DE MI ENTREGA?
     Vaya a la sección de "Mis Pedidos", abra el detalle de su compra y ahí visualizará el Timeline en tiempo real con las etapas de: Pedido Recibido -> Pago Reportado -> Pago Validado -> Preparando / En Camino -> Entregado.

10.5 ¿CÓMO DESCARGO EL COMPROBANTE O PDF DE MI PEDIDO?
     Abra el detalle de su pedido desde el historial de "Mis Pedidos" y pulse el botón "Descargar PDF". También puede descargar la Factura en PDF si el estado del pago ya fue validado por la administración.

10.6 ¿POR QUÉ NO VEO EL PANEL DE ADMINISTRACIÓN?
     El panel de administración es exclusivo para usuarios con rol de Administrador o Gerente de Operaciones. Si es cliente, no tiene acceso a esta sección.

10.7 ¿PUEDO COMPRAR PRODUCTOS Y SERVICIOS EN EL MISMO PEDIDO?
     Sí, la plataforma permite consolidar tanto productos físicos (como extintores) como servicios de mantenimiento e instalación en el mismo carrito y en el mismo pedido final. Ambos tipos de ítems se listarán juntos.

================================================================================
Fin del Manual de Usuario
