<?php
/**
 * Sistema de Registro de Asistenca y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */

/**
 * Configuración de conexiones a bases de datos
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class DATABASE_CONFIG {

/**
 * Predeterminada
 *
 * @var array
 */
	public $default = array(
		'database' => '',
		'datasource' => 'Database/Mysql',
		'encoding' => 'utf8',
		'host' => '',
		'login' => '',
		'password' => ''
	);
}
