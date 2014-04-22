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
		'Security' => array('blackHoleCallback' => 'blackHole'),
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

/**
 * Responde a solicitudes invalidadas por el componente Security
 *
 * @param null|string $type Tipo de error
 *
 * @return void
 */
	public function blackHole($type = null) {
		$this->Session->setFlash('Se ha rechazado la solicitud debido a que los datos recibidos no son válidos.');

		$action = $this->request->action;
		if (isset($this->request->prefix)) {
			$action = str_replace($this->request->prefix . '_', '', $action);
		}
		$this->redirect(compact('action'));
	}
}
