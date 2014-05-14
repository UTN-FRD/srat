## Sistema de Registro de Asistencia y Temas

Sistema de registro de asistencia y temas en la UTN Regional Delta que considere la asistencia a materias y casos
especiales como ser laboratorios y talleres.

## Requerimientos

* Apache 2.4.x
  * Módulos: rewrite
* MySQL 5.5.x
* PHP 5.4.x
* CakePHP 2.4.x
* [wkhtmltopdf](http://wkhtmltopdf.org/) (necesario para generar reportes)

## Instalación

### Sistema

* Descargar la última versión desde https://github.com/jalbertocr/srat/releases
* Descomprimir el archivo y copiar o mover la carpeta `srat` a la raíz del directorio público del servidor web
* Importar el esquema de la base de datos ubicado en `srat/app/Config/Schema/srat.sql` utilizando MySQL
desde la línea de comandos, phpMyAdmin o similar
* Abrir `srat/app/Config/database.php` y definir los parámetros de la conexión predeterminada a la base de datos
* Verificar que el usuario bajo el que corre el servidor web tiene permisos de escritura dentro del directorio
`srat/app/tmp` y subdirectorios
* Ingresar al sistema en http://127.0.0.1/srat con el usuario predeterminado:
  * Legajo: 1
  * Contraseña: demo
