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
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */

/**
 * Dependencias
 */
App::uses('CakeLog', 'Log');

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
	'Search'
));
