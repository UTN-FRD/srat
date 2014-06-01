<?php
/**
 * Configuración de la copia de seguridad de la base de datos
 *
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */

/**
 * Opciones de configuración
 *
 * - (boolean) `compress`
 * Comprime el archivo de la copia de seguridad.
 *
 * - (string) `dest`
 * Ruta de acceso al directorio donde se van a guardar las copias de seguridad.
 *
 * - (string) `filename`
 * Nombre del archivo de la copia de seguridad sin extensión. Este valor se pasa a la función
 * `strftime()` por lo que es posible utilizar los caracteres de formato para fecha y hora.
 *
 * - (string) `mysqldump`
 * Ruta de acceso al ejecutable mysqldump.
 *
 * - (string) `options`
 * Opciones adicionales pasadas a mysqldump.
 */
$config = array(
	'Backup' => array(
		'compress' => true,
		'dest' => TMP,
		'filename' => 'srat_%Y-%m-%d',
		'mysqldump' => '/usr/bin/mysqldump',
		'options' => '--single-transaction'
	)
);
