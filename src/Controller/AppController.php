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
 * Dependencias
 */
App::uses('Controller', 'Controller');

/**
 * Controlador de la aplicación
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class AppController extends Controller {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Security',
		'Session',
		'DebugKit.Toolbar'
	);

/**
 * Ayudantes
 *
 * @var array
 */
	public $helpers = array(
		'Form',
		'Html',
		'Session'
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->response->disableCache();
	}
}
