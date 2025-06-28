# Funcionalidad de Exportación de Movimientos de Stock

## Descripción

Se ha implementado una funcionalidad completa de exportación para el historial de movimientos de stock que permite descargar los datos en diferentes formatos: CSV, Excel y PDF.

## Características

### ✅ Formatos Soportados

1. **CSV (Comma Separated Values)**
   - Formato estándar para hojas de cálculo
   - Compatible con Excel, Google Sheets, etc.
   - Incluye BOM UTF-8 para caracteres especiales

2. **Excel (.xlsx)**
   - Formato nativo de Microsoft Excel
   - Fallback a formato TSV si no hay librería PhpSpreadsheet
   - Compatible con todas las versiones de Excel

3. **PDF**
   - Reporte formateado y profesional
   - Fallback a HTML para impresión si no hay TCPDF
   - Incluye estilos CSS para mejor presentación

### ✅ Filtros Aplicados

La exportación respeta todos los filtros aplicados en la vista:
- **Almacén**: Filtra por almacén de origen o destino
- **Producto**: Filtra por nombre de producto
- **Tipo de Movimiento**: Entrada, Salida, Transferencia, Ajuste
- **Rango de Fechas**: Desde y hasta fechas específicas

### ✅ Datos Exportados

Cada exportación incluye:
- ID del Movimiento
- Nombre del Producto
- Tipo de Movimiento
- Cantidad
- Fecha y Hora
- Almacén de Origen
- Almacén de Destino
- Usuario que realizó el movimiento

## Cómo Usar

### 1. Desde la Interfaz Web

1. Navega al **Historial de Movimientos**
2. Aplica los filtros deseados (opcional)
3. Haz clic en el botón **"Exportar"**
4. Selecciona el formato deseado:
   - 📄 **CSV**: Para análisis en hojas de cálculo
   - 📊 **Excel**: Para reportes profesionales
   - 📋 **PDF**: Para impresión y archivo

### 2. URLs Directas

Puedes acceder directamente a la exportación:

```bash
# Exportar todos los movimientos en CSV
http://localhost/InventoryManagementSystem/app/Controller/stock/exportarMovimientosController.php?formato=csv

# Exportar con filtros
http://localhost/InventoryManagementSystem/app/Controller/stock/exportarMovimientosController.php?formato=excel&almacen=Principal&tipo=entrada&fecha_desde=2024-01-01&fecha_hasta=2024-12-31

# Exportar en PDF
http://localhost/InventoryManagementSystem/app/Controller/stock/exportarMovimientosController.php?formato=pdf
```

### 3. Parámetros Disponibles

| Parámetro | Descripción | Ejemplo |
|-----------|-------------|---------|
| `formato` | Formato de exportación | `csv`, `excel`, `pdf` |
| `almacen` | Filtrar por almacén | `Principal`, `Secundario` |
| `producto` | Filtrar por producto | `Laptop`, `Mouse` |
| `tipo` | Tipo de movimiento | `entrada`, `salida`, `transferencia`, `ajuste` |
| `fecha_desde` | Fecha inicial | `2024-01-01` |
| `fecha_hasta` | Fecha final | `2024-12-31` |

## Archivos Implementados

### Controladores
- `app/Controller/stock/exportarMovimientosController.php` - Controlador principal de exportación

### Modelos
- `app/Models/stock/stock.php` - Métodos `obtenerMovimientos()` y `contarMovimientosPorTipo()`

### Vistas
- `app/Views/stock/movimientoStockVista.php` - Vista actualizada con menú de exportación

### Estilos
- `public/css/historialMovimientos.css` - Estilos modernos y responsivos

## Características Técnicas

### 🔧 Funcionalidades Implementadas

1. **Menú Desplegable Interactivo**
   - Animaciones suaves
   - Indicadores de carga
   - Tooltips informativos

2. **Responsive Design**
   - Adaptable a móviles y tablets
   - Menú optimizado para pantallas pequeñas

3. **Manejo de Errores**
   - Validación de datos
   - Mensajes de error informativos
   - Fallbacks para librerías faltantes

4. **Optimización de Rendimiento**
   - Consultas SQL optimizadas
   - Paginación para grandes volúmenes
   - Streaming de archivos grandes

### 🎨 Mejoras Visuales

1. **Diseño Moderno**
   - Gradientes atractivos
   - Sombras y efectos hover
   - Iconografía FontAwesome

2. **Métricas Visuales**
   - Tarjetas con contadores por tipo
   - Colores diferenciados por categoría
   - Animaciones de entrada

3. **Tabla Mejorada**
   - Badges coloridos para tipos
   - Hover effects en filas
   - Paginación estilizada

## Requisitos del Sistema

### Librerías Opcionales (para funcionalidad completa)

```bash
# Para exportación Excel avanzada
composer require phpoffice/phpspreadsheet

# Para exportación PDF avanzada
composer require tecnickcom/tcpdf
```

### Sin estas librerías, el sistema funciona con fallbacks:
- Excel → Archivo TSV con extensión .xlsx
- PDF → HTML optimizado para impresión

## Ejemplos de Uso

### Exportar Movimientos de un Almacén Específico
```bash
curl "http://localhost/InventoryManagementSystem/app/Controller/stock/exportarMovimientosController.php?formato=csv&almacen=Principal"
```

### Exportar Entradas del Último Mes
```bash
curl "http://localhost/InventoryManagementSystem/app/Controller/stock/exportarMovimientosController.php?formato=excel&tipo=entrada&fecha_desde=2024-11-01&fecha_hasta=2024-11-30"
```

### Exportar Transferencias en PDF
```bash
curl "http://localhost/InventoryManagementSystem/app/Controller/stock/exportarMovimientosController.php?formato=pdf&tipo=transferencia"
```

## Mantenimiento

### Agregar Nuevos Formatos

Para agregar un nuevo formato de exportación:

1. Agregar el caso en el `switch` del método `exportarMovimientos()`
2. Implementar el método correspondiente (ej: `exportarJSON()`)
3. Agregar la opción en el menú desplegable de la vista

### Personalizar Estilos

Los estilos están en `public/css/historialMovimientos.css` y se pueden personalizar fácilmente.

## Soporte

Si encuentras algún problema con la exportación:

1. Verifica que tienes permisos de escritura en el directorio temporal
2. Comprueba que la base de datos está accesible
3. Revisa los logs de error de PHP
4. Asegúrate de que los filtros aplicados son válidos

---

**Desarrollado para el Sistema de Gestión de Inventarios** 🚀 