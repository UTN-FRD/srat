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
 * AsignaturasCarreras
 */
Router::connect('/admin/asignaturas/carreras', array(
	'controller' => 'asignaturas_carreras',
	'action' => 'index',
	'admin' => true
));
Router::connect('/admin/asignaturas/carreras/:action/*', array(
	'controller' => 'asignaturas_carreras',
	'admin' => true
));

/**
 * AsignaturasMaterias
 */
Router::connect('/admin/asignaturas/materias', array(
	'controller' => 'asignaturas_materias',
	'action' => 'index',
	'admin' => true
));
Router::connect('/admin/asignaturas/materias/:action/*', array(
	'controller' => 'asignaturas_materias',
	'admin' => true
));

/**
 * Horarios
 */
Router::connect('/admin/asignaturas/horarios', [
	'controller' => 'horarios',
	'action' => 'index',
	'admin' => true
]);
Router::connect('/admin/asignaturas/horarios/:action/*', [
	'controller' => 'horarios',
	'admin' => true
]);

/**
 * Cargos
 */
Router::connect('/admin/asignaturas/usuarios', [
	'controller' => 'cargos',
	'action' => 'index',
	'admin' => true
]);
Router::connect('/admin/asignaturas/usuarios/:action/*', [
	'controller' => 'cargos',
	'admin' => true
]);

/**
 * Rutas de los plugins
 */
CakePlugin::routes();

/**
 * Rutas predeterminadas de CakePHP
 */
require CAKE . 'Config' . DS . 'routes.php';
