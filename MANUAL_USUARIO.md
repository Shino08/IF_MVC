================================================================================
MANUAL DE USUARIO — Sistema de Cotizaciones InstalFuego (IF_MVC)
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
    - Boton "Agregar a Cotizacion"

2.3 DETALLE DE SERVICIO
    Al hacer clic en un servicio, se muestra:
    - Imagen del servicio
    - Nombre, codigo
    - Descripcion del servicio
    - Precio referencial
    - Tipo de cobro (Por hora, Por unidad, Por metro lineal, Por proyecto)
    - Boton "Agregar a Cotizacion"


3. SOLICITUD DE COTIZACION
--------------------------------------------------------------------------------
El sistema permite armar una solicitud de cotizacion con productos y/o
servicios antes de enviarla al equipo de InstalFuego.

3.1 AGREGAR ITEMS A LA COTIZACION
    Desde el detalle de cualquier producto o servicio:
    1. Haga clic en "Agregar a Cotizacion".
    2. En el modal, seleccione la cantidad deseada.
    3. Haga clic en "Agregar".
    4. El item se agregara a su lista de cotizacion actual.

    Tambien puede ir directamente a "Cotizacion Actual" en el menu
    para ver todos los items agregados.

3.2 GESTIONAR LA LISTA DE COTIZACION
    En la pagina "Cotizacion Actual" (/cotizacion/actual) puede:
    - Ver todos los items agregados con sus cantidades y precios
    - Modificar cantidades usando los botones +/- o escribiendo
      directamente
    - Eliminar items individuales con el icono de papelera
    - Ver el subtotal actualizado en tiempo real

3.3 ENVIAR LA SOLICITUD
    Cuando tenga todos los items deseados en su lista:
    1. Agregue notas tecnicas o comentarios sobre su solicitud
       (opcional).
    2. Haga clic en "Enviar Solicitud de Cotizacion".
    3. La solicitud se enviara al equipo de InstalFuego para su
       revision y cotizacion formal.
    4. Recibira una confirmacion del envio exitoso.

    NOTA: No puede enviar una solicitud vacia. Debe tener al menos
    un producto o servicio agregado.


4. HISTORIAL DE COTIZACIONES
--------------------------------------------------------------------------------
En la seccion "Mis Cotizaciones" (/mis-cotizaciones) puede ver el
historial completo de todas sus solicitudes enviadas.

Cada entrada del historial muestra:
- Numero de solicitud (#COT-XXXXXX)
- Fecha de solicitud
- Estado actual (Pendiente, Enviada, Aprobada, Rechazada)
- Monto total cotizado (si ya fue procesada)
- Enlace para ver el detalle completo

Los estados de una cotizacion son:
  - Borrador: Aun no enviada, solo visible para usted
  - Pendiente de Revision: Enviada, esperando respuesta del equipo
  - Enviada: Cotizacion formal emitida por InstalFuego, lista para
    su revision
  - Aprobada: Usted confirmo su interes en la cotizacion
  - Rechazada: La solicitud fue rechazada por el equipo
  - Vencida: La cotizacion perdio su vigencia


5. VISUALIZACION DE COTIZACIONES
--------------------------------------------------------------------------------
Al hacer clic en una cotizacion del historial, se muestra el documento
formal tipo factura que incluye:

5.1 ENCABEZADO
    - Logotipo de InstalFuego C.A.
    - Datos de la empresa (RIF, direccion, contacto)
    - Numero de cotizacion (#COT-AÑO-XXXX)
    - Fecha de emision
    - Fecha de vencimiento
    - Estado actual de la cotizacion

5.2 DATOS DEL CLIENTE
    - Nombre completo
    - Empresa
    - CI/RIF
    - Correo electronico
    - Telefono

5.3 DETALLE DE ITEMS
    Tabla con:
    - Descripcion del item (producto o servicio)
    - Tipo (Producto o Servicio)
    - Cantidad solicitada
    - Precio unitario en USD
    - Subtotal por linea

5.4 TOTALES
    - Subtotal (suma de todos los items)
    - IVA / Impuestos (si aplica)
    - Descuentos (si aplican)
    - Total final en USD

5.5 NOTAS Y CONDICIONES
    - Notas tecnicas y comerciales
    - Terminos y condiciones
    - Metodo de pago
    - Condiciones de pago
    - Proyecto de referencia (si aplica)


6. DESCARGA DE PDF
--------------------------------------------------------------------------------
Desde la pagina de detalle de cualquier cotizacion (tanto en el historial
como en la vista de cliente), puede descargar la cotizacion en formato PDF.

Pasos:
  1. Abra el detalle de la cotizacion.
  2. Haga clic en el boton "Descargar PDF".
  3. El PDF se abrira en una nueva pestana o se descargara
     automaticamente, dependiendo de su navegador.

El PDF incluye toda la informacion del documento: encabezado, datos del
cliente, items, totales, notas y terminos. Es apto para imprimir y
presentar como documento formal.


7. PANEL DE ADMINISTRACION (SOLO PERSONAL AUTORIZADO)
--------------------------------------------------------------------------------
El panel de administracion esta disponible solo para usuarios con rol
de Administrador o Gerente de Operaciones. No es accesible para clientes.

7.1 DASHBOARD
    Pagina principal del panel que muestra:
    - KPIs: total de productos, servicios, categorias
    - Alertas de stock (productos sin existencia)
    - Distribucion de productos por categoria (grafico de barras)
    - Lista de servicios activos
    - Ultimos productos agregados

7.2 GESTION DE PRODUCTOS
    Permite:
    - Ver listado completo con busqueda por SKU/nombre
    - Agregar nuevo producto con hasta 5 imagenes
    - Editar producto (datos, precios, stock, imagenes)
    - Reemplazar o eliminar imagenes individualmente
    - Eliminar productos

    Campos del producto:
    - Nombre, SKU, Categoria, Precio, Marca, Modelo
    - Stock, Descripcion tecnica
    - Imagen principal + galeria de imagenes

7.3 GESTION DE SERVICIOS
    Permite:
    - Ver listado con busqueda y filtro por categoria
    - Agregar nuevo servicio
    - Editar servicio
    - Eliminar servicio

    Campos del servicio:
    - Codigo, Nombre, Categoria, Precio Referencial
    - Tipo de Cobro (Por hora, Por unidad, etc.)
    - Descripcion, Imagen

7.4 GESTION DE CATEGORIAS
    Permite crear y eliminar categorias para clasificar productos
    y servicios.

7.5 GESTION DE SOLICITUDES DE COTIZACION
    Flujo completo de gestion comercial mediante wizard de 3 pasos:

    PASO 1: REVISAR ITEMS
    - Visualizar los items solicitados por el cliente
    - Ajustar precios unitarios (se guardan automaticamente)
    - Modificar cantidades
    - Eliminar items si es necesario
    - Ver datos del cliente

    PASO 2: CONFIGURAR
    - Fecha de vencimiento de la cotizacion
    - Descuento aplicado (en USD)
    - IVA / Impuestos (en USD)
    - Metodo de pago aceptado
    - Condiciones de pago (ej: 50% anticipo, 50% contra entrega)
    - Proyecto/Referencia
    - Guardar cambios y continuar

    PASO 3: REVISAR Y EMITIR
    - Notas internas (solo visibles para el equipo)
    - Notas para el cliente (visibles en PDF)
    - Resumen de la cotizacion
    - Acciones: Emitir cotizacion o Rechazar con motivo
    - Acceso a vista previa como cliente y descarga de PDF

7.6 REPORTES
    Generacion de reportes con filtros:
    - Tipo de reporte: Solicitudes de Cotizacion / Mas Solicitados
    - Filtro por estado (Pendiente, Procesada, Rechazada)
    - Rango de fechas
    - Exportacion a CSV
    - Visualizacion de totales y tasas de conversion


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

8.3 MIS SOLICITUDES (/mis-cotizaciones)
    Acceso directo al historial completo de cotizaciones.


9. TIPOS DE USUARIO Y PERMISOS
--------------------------------------------------------------------------------
El sistema maneja tres roles con diferentes niveles de acceso:

  ADMIN (rol_id = 1)
  - Acceso completo al panel de administracion
  - Gestion de productos, servicios, categorias
  - Gestion de cotizaciones (revisar, ajustar, emitir, rechazar)
  - Reportes y exportacion
  - Envio de cotizaciones por correo al cliente

  GERENTE DE OPERACIONES (rol_id = 3)
  - Acceso al panel de administracion
  - Gestion de cotizaciones (revisar, ajustar, emitir, rechazar)
  - Reportes y exportacion
  - Gestion de servicios
  - SIN acceso a gestion de productos

  CLIENTE (rol_id = 2)
  - Catalogo de productos y servicios
  - Solicitud de cotizaciones (armar, enviar)
  - Historial de cotizaciones
  - Perfil de usuario
  - SIN acceso al panel de administracion


10. PREGUNTAS FRECUENTES
--------------------------------------------------------------------------------

10.1 ¿COMO SOLICITO UNA COTIZACION?
     Navegue el catalogo, haga clic en "Agregar a Cotizacion" en los
     productos o servicios de su interes, luego vaya a "Cotizacion Actual"
     y haga clic en "Enviar Solicitud".

10.2 ¿PUEDO MODIFICAR UNA SOLICITUD ENVIADA?
     No, una vez enviada la solicitud no puede modificarse. Si necesita
     cambios, contacte al equipo de InstalFuego directamente.

10.3 ¿CUANTO TIEMPO TARDA UNA COTIZACION?
     El equipo de InstalFuego revisara su solicitud y emitira una
     cotizacion formal. El tiempo de respuesta depende de la complejidad
     de los items solicitados.

10.4 ¿QUE SIGNIFICA EL ESTADO "ENVIADA"?
     Significa que el equipo de InstalFuego ya reviso su solicitud y
     emitio una cotizacion formal con precios, descuentos y condiciones
     comerciales. Puede ver el detalle completo y descargar el PDF.

10.5 ¿COMO DESCARGO UNA COTIZACION EN PDF?
     Abra el detalle de la cotizacion desde "Mis Cotizaciones" y haga
     clic en "Descargar PDF".

10.6 ¿POR QUE NO VEO EL PANEL DE ADMINISTRACION?
     El panel de administracion es exclusivo para usuarios con rol de
     Administrador o Gerente de Operaciones. Si es cliente, no tiene
     acceso a esta seccion.

10.7 ¿PUEDO AGREGAR PRODUCTOS Y SERVICIOS EN LA MISMA COTIZACION?
     Si, puede agregar tanto productos como servicios en una misma
     solicitud de cotizacion. Ambos tipos de items se listaran juntos
     en el detalle.

================================================================================
Fin del Manual de Usuario
