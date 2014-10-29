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

* Descargar la última versión desde https://github.com/jacricelli/srat/releases
* Descomprimir el archivo y copiar o mover la carpeta `srat` a la raíz del directorio público del servidor web
* Importar el esquema de la base de datos ubicado en `srat/app/Config/Schema/srat.sql` utilizando MySQL
desde la línea de comandos, phpMyAdmin o similar
* Abrir `srat/app/Config/database.php` y definir los parámetros de la conexión predeterminada a la base de datos
* Verificar que el usuario bajo el que corre el servidor web tiene permisos de escritura dentro del directorio
`srat/app/tmp` y subdirectorios
* Ingresar al sistema en http://127.0.0.1/srat con el usuario predeterminado:
  * Legajo: 1
  * Contraseña: demo

### wkhtmltopdf

#### Instalación

* Descargar la última versión desde http://wkhtmltopdf.org/downloads.html
  * **Nota:** No son compatibles las versiones disponibles en los repositorios de algunas distribuciones de Linux.

##### Linux

* Descomprimir el archivo
* Mover ejecutable `wkhtmltox-<version>/bin/wkhtmltopdf` a `/usr/bin`

```bash
tar xfvJ wkhtmltox-*.tar.xz
chmod a+x wkhtmltox-*/bin/wkhtmltopdf
mv wkhtmltox-*/bin/wkhtmltopdf /usr/bin
```
##### Winodws

* Ejecutar instalador y seguir los pasos en pantalla

#### Configuración

**Nota:** No hay necesidad de configurar nada si wkhtmltopdf ya se encuentra instalado en `/usr/bin`.

* Abrir `srat/app/Config/bootstrap.php` con un editor de textos
* Ir al final del archivo donde se encuentra `Ruta de acceso a wkhtmltopdf`
* Definir la nueva ruta de acceso a wkhtmltopdf
  * **Nota:** Es recomendable que la ruta de acceso no contenga espacios.
* Guardar los cambios y cerrar el archivo

```php
# Linux
Configure::write('CakePdf.binary', '/home/usuario/Downloads/wkhtmltopdf');

# Windows
Configure::write('CakePdf.binary', 'C:\wkhtmltopdf\bin\wkhtmltopdf.exe');
```

## Desarrollo

### Requerimientos

* [Composer](https://getcomposer.org/)
* [yUglify](https://github.com/yui/yuglify) (necesario para generar la aplicación)

### Instalación

* Clonar el repositorio en la raíz del directorio público del servidor web

```bash
git clone git@github.com:jacricelli/srat.git srat
```
* Ingresar en el directorio `srat` y ejecutar

```bash
composer update
```
**Nota:** Ignorar la advertencia relacionada con la resolución ambigua de clases

* Importar el esquema de la base de datos ubicado en `srat/app/Config/Schema/srat.sql` utilizando MySQL
desde la línea de comandos, phpMyAdmin o similar
* Abrir `srat/app/Config/database.php` y definir los parámetros de la conexión predeterminada y pruebas
* Verificar que el usuario bajo el que corre el servidor web tiene permisos de escritura dentro del directorio
`srat/app/tmp` y subdirectorios
* Ingresar al sistema en http://127.0.0.1/srat con el usuario predeterminado:
  * Legajo: 1
  * Contraseña: demo

### Generar aplicación

* Ingresar en el directorio `srat` y ejecutar

```bash
vendor/bin/phing build
```

* La aplicación generada se encuentra en `srat/build/package`
