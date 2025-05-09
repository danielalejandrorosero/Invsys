# Guía de Instalación - Sistema de Gestión de Inventario (InvSys)

## Requisitos Previos

### Requisitos de Software
- PHP 8.0 o superior
- MySQL 5.7 o superior
- Servidor web Apache/Nginx
- Composer (gestor de dependencias de PHP)
- Navegador web moderno (Chrome, Firefox, Edge, etc.)

### Requisitos de Hardware Recomendados
- Procesador: 2 GHz o superior
- Memoria RAM: 4 GB o superior
- Espacio en disco: 500 MB para la aplicación + espacio para la base de datos

## Proceso de Instalación

### 1. Configuración del Servidor Web

Si utiliza XAMPP (recomendado para entornos de desarrollo):

1. Descargue e instale XAMPP desde [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Inicie los servicios de Apache y MySQL desde el panel de control de XAMPP

### 2. Instalación de la Base de Datos

1. Acceda a phpMyAdmin a través de [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Cree una nueva base de datos llamada `ims_invsys`
3. Importe el archivo SQL ubicado en `config/IMS_INVENTORY.sql`

### 3. Configuración del Proyecto

1. Clone o descargue el repositorio en el directorio `htdocs` de su servidor web
2. Navegue hasta la carpeta del proyecto
3. Instale las dependencias utilizando Composer:

```bash
composer install
```

4. Cree un archivo `.env` en la carpeta raíz del proyecto con la siguiente configuración (ajuste según su entorno):

```
DB_SERVER=localhost
DB_USERNAME=root
DB_PASSWORD=
DB_DATABASE=ims_invsys
TIMEZONE=America/Bogota
```

### 4. Configuración de Permisos

Asegúrese de que el servidor web tenga permisos de escritura en las siguientes carpetas:

- `public/uploads/`
- `public/uploads/imagenes/`

En sistemas basados en Unix/Linux:

```bash
chmod -R 755 public/uploads/
chown -R www-data:www-data public/uploads/
```

### 5. Acceso al Sistema

1. Abra su navegador web y acceda a [http://localhost/InventoryManagementSystem/public/](http://localhost/InventoryManagementSystem/public/)
2. Utilice las siguientes credenciales para iniciar sesión por primera vez:
   - Usuario: admin@invsys.com
   - Contraseña: admin123

> **Importante**: Por seguridad, cambie la contraseña del administrador inmediatamente después del primer inicio de sesión.

## Solución de Problemas Comunes

### Error de Conexión a la Base de Datos

Si aparece un error de conexión a la base de datos:

1. Verifique que el servicio MySQL esté en ejecución
2. Compruebe que las credenciales en el archivo `.env` sean correctas
3. Asegúrese de que la base de datos `ims_invsys` exista

### Error de Permisos

Si aparecen errores relacionados con permisos de archivos:

1. Verifique que las carpetas mencionadas en la sección de permisos tengan los permisos adecuados
2. En entornos Windows, asegúrese de que el usuario del servidor web tenga permisos de escritura en esas carpetas

### Error de Carga de Imágenes

Si no puede cargar imágenes de productos:

1. Verifique que la carpeta `public/uploads/imagenes/` exista y tenga permisos de escritura
2. Compruebe que el tamaño de las imágenes no exceda el límite configurado en PHP (`upload_max_filesize` y `post_max_size` en php.ini)

## Actualización del Sistema

Para actualizar el sistema a una nueva versión:

1. Realice una copia de seguridad de la base de datos y los archivos del proyecto
2. Descargue la nueva versión del sistema
3. Reemplace los archivos existentes con los nuevos
4. Ejecute las migraciones de la base de datos si es necesario
5. Actualice las dependencias con Composer:

```bash
composer update
```

---

*Guía de Instalación - Sistema de Gestión de Inventario (InvSys)*
*Versión 1.0*