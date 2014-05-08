<?php
/**
 * Configuración de CakePHP
 *
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
 * Nivel de depuración
 */
Configure::write('debug', 0);

/**
 * Configuración del manejador de errores
 */
Configure::write('Error', array(
	'handler' => 'ErrorHandler::handleError',
	'level' => E_ALL & ~E_DEPRECATED,
	'trace' => true
));

/**
 * Configuración del manejador de excepciones
 */
Configure::write('Exception', array(
	'handler' => 'ErrorHandler::handleException',
	'log' => true,
	'renderer' => 'AppExceptionRenderer'
));

/**
 * Ruta base en caso que la aplicación se encuentre en un subdirectorio
 */
Configure::write('App.base', DS . basename(dirname(dirname(__DIR__))));

/**
 * Codificación de caracteres
 */
Configure::write('App.encoding', 'UTF-8');

/**
 * Idioma
 */
Configure::write('Config.language', 'spa');

/**
 * Zona horaria
 */
Configure::write('Config.timezone', 'America/Argentina/Buenos_Aires');

/**
 * Prefijos de las rutas
 */
Configure::write('Routing.prefixes', array('admin'));

/**
 * Deshabilitar cache
 */
Configure::write('Cache.disable', false);

/**
 * Habilitar comprobación del cache
 */
Configure::write('Cache.check', true);

/**
 * Configuración de la sesión
 */
Configure::write('Session', array(
	'cookie' => 'utn_srat',
	'defaults' => 'cache',
	'timeout' => 720
));

/**
 * Cadena al azar usada por los métodos de seguridad
 */
Configure::write('Security.salt', 'e41240b96a8bf79004a616257682792e4a69a9b5');

/**
 * Cadena numérica al azar usada por los métodos de seguridad
 */
Configure::write('Security.cipherSeed', '376138663337353532663361366338');

/**
 * Cache del core del framework
 */
Cache::config('_cake_core_', array(
	'duration' => '+12 months',
	'engine' => 'Apc',
	'path' => CACHE . 'persistent' . DS,
	'prefix' => APP_DIR . '_cake_core_'
));

/**
 * Cache de modelos y orígenes de datos
 */
Cache::config('_cake_model_', array(
	'duration' => '+12 months',
	'engine' => 'Apc',
	'path' => CACHE . 'models' . DS,
	'prefix' => APP_DIR . '_cake_model_'
));
