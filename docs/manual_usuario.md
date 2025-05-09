# Manual de Usuario - Sistema de Gestión de Inventario (InvSys)

## Índice
1. [Introducción](#introducción)
2. [Acceso al Sistema](#acceso-al-sistema)
3. [Panel Principal](#panel-principal)
4. [Gestión de Productos](#gestión-de-productos)
5. [Gestión de Inventario](#gestión-de-inventario)
6. [Gestión de Usuarios](#gestión-de-usuarios)
7. [Gestión de Proveedores](#gestión-de-proveedores)
8. [Reportes](#reportes)
9. [Asistente Virtual (Chatbot)](#asistente-virtual-chatbot)
10. [Preguntas Frecuentes](#preguntas-frecuentes)

## Introducción

Bienvenido al Sistema de Gestión de Inventario (InvSys), una herramienta diseñada para facilitar el control y seguimiento de su inventario. Este manual le guiará a través de las diferentes funcionalidades del sistema, explicando paso a paso cómo realizar las operaciones más comunes.

## Acceso al Sistema

### Iniciar Sesión

1. Abra su navegador web y acceda a la dirección del sistema.
2. En la pantalla de inicio de sesión, ingrese su correo electrónico y contraseña.
3. Haga clic en el botón "Iniciar Sesión".

![Pantalla de Inicio de Sesión](../config/descarga.png)

### Recuperación de Contraseña

Si ha olvidado su contraseña:

1. En la pantalla de inicio de sesión, haga clic en "¿Olvidó su contraseña?".
2. Ingrese su correo electrónico registrado.
3. Siga las instrucciones enviadas a su correo para restablecer su contraseña.

## Panel Principal

Una vez que haya iniciado sesión, accederá al panel principal del sistema, donde encontrará:

- **Resumen de Inventario**: Muestra la cantidad total de productos y el valor del inventario.
- **Alertas de Stock**: Productos que están por debajo del nivel mínimo establecido.
- **Movimientos Recientes**: Últimas entradas y salidas de productos.
- **Acceso Rápido**: Botones para acceder a las funciones más utilizadas.

## Gestión de Productos

### Listar Productos

1. En el menú lateral, haga clic en "Productos" > "Listar Productos".
2. Verá una tabla con todos los productos registrados en el sistema.
3. Puede utilizar el campo de búsqueda para filtrar productos por nombre.
4. La tabla muestra información básica como nombre, precio y categoría.

### Agregar Nuevo Producto

1. En la pantalla de Listar Productos, haga clic en el botón "Agregar Producto".
2. Complete el formulario con la información del producto:
   - Nombre del producto
   - Descripción
   - Categoría (seleccione de la lista desplegable)
   - Proveedor (seleccione de la lista desplegable)
   - Precio de compra
   - Precio de venta
   - Stock mínimo
   - Stock máximo
   - Imagen del producto (opcional)
3. Haga clic en "Guardar" para registrar el nuevo producto.

### Editar Producto

1. En la lista de productos, localice el producto que desea modificar.
2. Haga clic en el botón de edición (ícono de lápiz).
3. Actualice la información necesaria en el formulario.
4. Haga clic en "Guardar Cambios".

### Eliminar Producto

1. En la lista de productos, localice el producto que desea eliminar.
2. Haga clic en el botón de eliminación (ícono de papelera).
3. Confirme la acción en el cuadro de diálogo que aparece.

> **Nota**: La eliminación de productos es lógica, lo que significa que el producto se marcará como inactivo pero no se borrará de la base de datos.

## Gestión de Inventario

### Ver Inventario

1. En el menú lateral, haga clic en "Inventario" > "Ver Inventario".
2. Seleccione el almacén del cual desea ver el inventario.
3. Haga clic en "Ver Inventario".
4. Se mostrará una tabla con todos los productos disponibles en ese almacén, incluyendo:
   - Nombre del producto
   - Cantidad disponible
   - Nivel de stock (barra visual)
   - Stock mínimo y máximo

### Ajustar Stock

1. En la pantalla de Ver Inventario, haga clic en el botón "Ajustar Stock".
2. Seleccione el almacén donde desea realizar el ajuste.
3. Seleccione el producto a ajustar.
4. Ingrese la cantidad a ajustar:
   - Para aumentar el stock, ingrese un número positivo.
   - Para disminuir el stock, ingrese un número negativo.
5. Seleccione el tipo de movimiento (entrada, salida, ajuste, etc.).
6. Agregue una observación si es necesario.
7. Haga clic en "Guardar Ajuste".

### Transferir Productos Entre Almacenes

1. En el menú lateral, haga clic en "Inventario" > "Transferir Productos".
2. Seleccione el almacén de origen.
3. Seleccione el almacén de destino.
4. Seleccione el producto a transferir.
5. Ingrese la cantidad a transferir.
6. Agregue una observación si es necesario.
7. Haga clic en "Realizar Transferencia".

### Alertas de Stock

1. En el menú lateral, haga clic en "Inventario" > "Alertas de Stock".
2. Verá una lista de todos los productos que están por debajo del nivel mínimo establecido.
3. Puede filtrar las alertas por almacén o por estado (pendiente/enviada).
4. Para marcar una alerta como atendida, haga clic en el botón "Marcar como Atendida".

## Gestión de Usuarios

> **Nota**: Esta sección solo está disponible para usuarios con nivel de acceso de Administrador (Nivel 1).

### Listar Usuarios

1. En el menú lateral, haga clic en "Usuarios" > "Listar Usuarios".
2. Verá una tabla con todos los usuarios registrados en el sistema.

### Agregar Usuario

1. En la pantalla de Listar Usuarios, haga clic en el botón "Agregar Usuario".
2. Complete el formulario con la información del usuario:
   - Nombre
   - Apellido
   - Correo electrónico
   - Contraseña
   - Nivel de acceso (1: Administrador, 2: Supervisor, 3: Operador)
3. Haga clic en "Guardar" para registrar el nuevo usuario.

### Editar Usuario

1. En la lista de usuarios, localice el usuario que desea modificar.
2. Haga clic en el botón de edición (ícono de lápiz).
3. Actualice la información necesaria en el formulario.
4. Haga clic en "Guardar Cambios".

### Eliminar Usuario

1. En la lista de usuarios, localice el usuario que desea eliminar.
2. Haga clic en el botón de eliminación (ícono de papelera).
3. Confirme la acción en el cuadro de diálogo que aparece.

## Gestión de Proveedores

### Listar Proveedores

1. En el menú lateral, haga clic en "Proveedores" > "Listar Proveedores".
2. Verá una tabla con todos los proveedores registrados en el sistema.

### Agregar Proveedor

1. En la pantalla de Listar Proveedores, haga clic en el botón "Agregar Proveedor".
2. Complete el formulario con la información del proveedor:
   - Nombre
   - Contacto
   - Teléfono
   - Correo electrónico
   - Dirección
3. Haga clic en "Guardar" para registrar el nuevo proveedor.

### Editar Proveedor

1. En la lista de proveedores, localice el proveedor que desea modificar.
2. Haga clic en el botón de edición (ícono de lápiz).
3. Actualice la información necesaria en el formulario.
4. Haga clic en "Guardar Cambios".

### Ver Productos por Proveedor

1. En la lista de proveedores, haga clic en el botón "Ver Productos" junto al proveedor deseado.
2. Se mostrará una lista de todos los productos asociados a ese proveedor.

## Reportes

### Reporte de Inventario

1. En el menú lateral, haga clic en "Reportes" > "Inventario".
2. Seleccione el almacén para el cual desea generar el reporte.
3. Seleccione el formato de salida (PDF, Excel, CSV).
4. Haga clic en "Generar Reporte".

### Reporte de Movimientos

1. En el menú lateral, haga clic en "Reportes" > "Movimientos".
2. Seleccione el rango de fechas para el reporte.
3. Seleccione el tipo de movimiento (opcional).
4. Seleccione el almacén (opcional).
5. Seleccione el formato de salida (PDF, Excel, CSV).
6. Haga clic en "Generar Reporte".

### Reporte de Valoración de Inventario

1. En el menú lateral, haga clic en "Reportes" > "Valoración".
2. Seleccione el almacén para el cual desea generar el reporte.
3. Seleccione el formato de salida (PDF, Excel, CSV).
4. Haga clic en "Generar Reporte".

## Asistente Virtual (Chatbot)

El sistema incluye un asistente virtual que puede ayudarle a realizar consultas rápidas sobre el inventario.

### Cómo Usar el Chatbot

1. Haga clic en el ícono de chat ubicado en la esquina inferior derecha de cualquier pantalla.
2. Se abrirá una ventana de chat.
3. Escriba su consulta en lenguaje natural. Por ejemplo:
   - "¿Cuántas unidades del producto X hay disponibles?"
   - "Muéstrame los productos con stock bajo"
   - "¿Cuál es el valor total del inventario?"
   - "Genera un informe de movimientos de la semana pasada"
4. El chatbot procesará su consulta y le mostrará la información solicitada.

## Preguntas Frecuentes

### ¿Cómo cambiar mi contraseña?

1. Haga clic en su nombre de usuario en la esquina superior derecha.
2. Seleccione "Mi Perfil".
3. Haga clic en "Cambiar Contraseña".
4. Ingrese su contraseña actual y la nueva contraseña.
5. Haga clic en "Guardar Cambios".

### ¿Qué hacer si un producto está agotado?

Cuando un producto está agotado, debe realizar un ajuste de stock para registrar la entrada de nuevas unidades:

1. Vaya a "Inventario" > "Ajustar Stock".
2. Seleccione el almacén y el producto agotado.
3. Ingrese la cantidad que está ingresando (número positivo).
4. Seleccione el tipo de movimiento "Entrada".
5. Complete la información adicional y guarde el ajuste.

### ¿Cómo activar el modo oscuro?

1. Haga clic en el ícono de luna/sol ubicado en la esquina superior derecha de la pantalla.
2. El sistema cambiará automáticamente entre el modo claro y oscuro.
3. Su preferencia se guardará para futuras sesiones.

---

*Manual de Usuario - Sistema de Gestión de Inventario (InvSys)*
*Versión 1.0*