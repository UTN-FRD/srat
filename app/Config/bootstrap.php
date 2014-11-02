<?php
/**
 * Configuración del arranque de la aplicación
 *
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

/**
 * Autocargador de clases de Composer
 */
require_once dirname(APP) . DS . 'vendor' . DS . 'autoload.php';

/**
 * Autocargador de clases de CakePHP
 * https://github.com/composer/composer/commit/c80cb76b9b5082ecc3e5b53b1050f76bb27b127b
 */
spl_autoload_unregister(array('App', 'load'));
spl_autoload_register(array('App', 'load'), true, true);

/**
 * Dependencias
 */
App::uses('CakeLog', 'Log');
App::uses('CakeTime', 'Utility');

/**
 * Localidad
 */
setlocale(LC_ALL, 'es_ES.UTF-8', 'esp');

/**
 * Zona horaria
 */
date_default_timezone_set(Configure::read('Config.timezone'));

/**
 * Reglas de inflexión para idioma español
 */
Inflector::rules('singular', array(
	'rules' => array(
		'/(ll)es$/i' => '\1e',
		'/([r|d|j|n|l|m|y|z])es$/i' => '\1',
		'/as$/i' => 'a',
		'/([ti])a$/i' => '\1a',
		'/is$/i' => '\1is',
	)
));
Inflector::rules('plural', array(
	'rules' => array(
		'/([r|d|j|n|l|m|y|z])$/i' => '\1es',
		'/is$/i' => '\1is',
		'/a$/i' => '\1as'
	)
));

/**
 * Registro de depuración
 */
CakeLog::config('debug', array(
	'engine' => 'FileLog',
	'file' => 'debug',
	'scopes' => array('debug', 'info', 'notice')
));

/**
 * Registro de error
 */
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'file' => 'error',
	'scopes' => array('alert', 'critical', 'emergency', 'error', 'warning')
));

/**
 * Filtros del despachador
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher'
));

/**
 * Configuración del cache
 */
Cache::config('default', array(
	'duration' => '+12 months',
	'engine' => 'File',
	'prefix' => APP_DIR . '_default_'
));

/**
 * Plugins
 */
CakePlugin::load(array(
	'CakePdf' => array('bootstrap' => true, 'routes' => true),
	'DebugKit',
	'Search'
));

/**
 * Ruta de acceso a wkhtmltopdf
 */
Configure::write('CakePdf.binary', '/usr/local/bin/wkhtmltopdf');
