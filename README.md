## Instalación del Proyecto RMA

### Requisitos

* XAMPP (Apache y MySQL)
* PHP 8.x o superior
* Base de datos configurada según el script proporcionado

### Estructura de Directorios

Para que el sistema funcione correctamente, el proyecto debe ubicarse exactamente en la siguiente ruta lógica:

```text
C:\xampp\htdocs\rma-app\adm-rma
```

### Pasos de Instalación

1. Ingresar al directorio `htdocs` de XAMPP:

```text
C:\xampp\htdocs\
```

2. Crear una carpeta llamada:

```text
rma-app
```

3. Copiar la carpeta del proyecto dentro de `rma-app`, de manera que la estructura final quede así:

```text
C:\xampp\htdocs\rma-app\adm-rma
```

4. Iniciar los servicios **Apache** y **MySQL** desde el panel de control de XAMPP.

5. Importar la base de datos correspondiente.

6. Acceder al sistema desde el navegador mediante:

```text
http://localhost/rma-app/adm-rma
```

### Importante

El sistema utiliza rutas configuradas tomando como base la ubicación:

```text
C:\xampp\htdocs\rma-app\adm-rma
```

Por lo tanto, se recomienda mantener exactamente esta estructura de carpetas para evitar errores de carga de módulos, recursos o redirecciones.
