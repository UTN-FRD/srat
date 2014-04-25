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
		'Auth' => array(
			'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'legajo'),
					'passwordHasher' => 'Blowfish',
					'recursive' => -1,
					'scope' => array('estado' => 1),
					'userModel' => 'Usuario'
				)
			),
			'authError' => 'La operación solicitada ha sido rechazada debido a que no cuenta con suficientes privilegios.',
			'authorize' => 'Controller',
			'loginAction' => array('controller' => 'usuarios', 'action' => 'login', 'admin' => false, 'plugin' => false),
			'loginRedirect' => array('controller' => 'usuarios', 'action' => 'dashboard', 'admin' => false, 'plugin' => false),
			'logoutRedirect' => array('controller' => 'usuarios', 'action' => 'login', 'admin' => false, 'plugin' => false)
		),
		'DebugKit.Toolbar'
	);

/**
 * Ayudantes
 *
 * @var array
 */
	public $helpers = array(
		'Form' => array('className' => 'MyForm'),
		'Html' => array('className' => 'MyHtml'),
		'Session'
	);

/**
 * Configuración de notificaciones
 *
 * @var array
 */
	protected $_notify = array();

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
 * beforeRender
 *
 * @return void
 */
	public function beforeRender() {
		parent::beforeRender();

		$this->_generateTitle();
	}

/**
 * Comprueba si un usuario tiene acceso a las acciones de un controlador
 *
 * @param array $user Datos del usuario
 *
 * @return boolean `true` en caso exitoso o `false` en caso contrario
 */
	public function isAuthorized($user = null) {
		if ($this->request->prefix === 'admin') {
			if ($user['rol_id'] != 1) {
				return false;
			}
		}
		return true;
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

/**
 * Establece el título para el diseño y/o la vista en caso que no se haya definido
 *
 * @return void
 */
	protected function _generateTitle() {
		$action = strtolower($this->request->action);
		if (isset($this->request->prefix)) {
			$action = str_replace($this->request->prefix . '_', '', $action);
		}

		foreach (array('title_for_layout', 'title_for_view') as $key) {
			if (!isset($this->viewVars[$key])) {
				if ($action == 'index') {
					$this->viewVars[$key] = Inflector::humanize($this->request->controller);
				} else {
					if ($key == 'title_for_layout') {
						$this->viewVars[$key] = sprintf('%s - %s',
							Inflector::humanize($action),
							Inflector::humanize($this->request->controller)
						);
					} else {
						$this->viewVars[$key] = sprintf('%s %s',
							Inflector::humanize($action),
							mb_strtolower(Inflector::singularize($this->request->controller))
						);
					}
				}
			}
		}
	}

/**
 * Genera una notificación
 *
 * Las configuraciones se definen en la propiedad `AppController::$_notify` o bien,
 * puede especificarse en el segundo parámetro de este método.
 *
 * ### Opciones
 *
 * (string) `level`
 * Nivel de notificación, `error`, `info`, `success` o `warning`.
 *
 * (string) `message`
 * Mensaje de la notificación.
 *
 * (boolean|array|string) `redirect`
 * El valor `true` realiza una redirección a la URL actual (actualizar URL). Una matriz o cadena
 * especifica la dirección URL a redireccionar.
 *
 * @param string|Exception $name Nombre de la configuración o instancia de una excepción
 * @param array $config Opciones de configuración en caso de no tener una configuración
 * definida o para modificar una existente
 *
 * @return void
 */
	protected function _notify($name = null, array $config = array()) {
		$default = array(
			'level' => 'error',
			'message' => 'No hay descripción del error.',
			'redirect' => false
		);

		if ($name instanceof Exception) {
			$default['message'] = $name->getMessage();
			$name = Inflector::underscore(str_replace('Exception', '', get_class($name)));
		}

		$options = array();
		if (isset($this->_notify[$name])) {
			$options = array_merge($this->_notify[$name], $config);
		} else {
			$options = array_merge($default, $config);
		}

		foreach (array_keys($default) as $key) {
			if (!isset($options[$key])) {
				$options[$key] = $default[$key];
			}
		}

		extract($options);
		$this->Session->setFlash($message, 'notify', compact('level'));

		if (!empty($redirect)) {
			if ($redirect === true) {
				$action = strtolower($this->request->action);
				if (isset($this->request->prefix)) {
					$action = str_replace($this->request->prefix . '_', '', $action);
				}
				$redirect = compact('action');
			}
			$this->redirect($redirect);
		}
	}
}
