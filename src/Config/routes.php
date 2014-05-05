<?php
/**
 * Configuración de las rutas
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
 * Índice
 */
Router::connect('/', array(
	'controller' => 'usuarios',
	'action' => 'dashboard'
));

/**
 * Inicio de sesión
 */
Router::connect('/login', array(
	'controller' => 'usuarios',
	'action' => 'login'
));

/**
 * Cierre de sesión
 */
Router::connect('/logout', array(
	'controller' => 'usuarios',
	'action' => 'logout'
));

/**
 * Docentes
 */
Router::connect('/docentes/*', array(
	'controller' => 'usuarios',
	'action' => 'docentes'
));

/**
 * Perfil
 */
Router::connect('/perfil', array(
	'controller' => 'usuarios',
	'action' => 'perfil'
));

/**
 * Restablecer contraseña
 */
Router::connect('/restablecer', array(
	'controller' => 'usuarios',
	'action' => 'restablecer'
));

/**
 * AsignaturasAreas
 */
Router::connect('/admin/asignaturas/areas', array(
	'controller' => 'asignaturas_areas',
	'action' => 'index',
	'admin' => true
));
Router::connect('/admin/asignaturas/areas/:action/*', array(
	'controller' => 'asignaturas_areas',
	'admin' => true
));

/**
 * Rutas de los plugins
 */
CakePlugin::routes();

/**
 * Rutas predeterminadas de CakePHP
 */
require CAKE . 'Config' . DS . 'routes.php';
