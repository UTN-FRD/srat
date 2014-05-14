<?php
/**
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
App::uses('AppController', 'Controller');

/**
 * TestsController
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class TestsController extends AppController {

/**
 * No renderizar vistas automáticamente
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * No incluir diseño al renderizar vistas
 *
 * @var boolean|string
 */
	public $layout = false;

/**
 * Modelos
 *
 * @var array
 */
	public $uses = array();

/**
 * Establece el título para el diseño y/o la vista en caso que no se haya definido
 *
 * @return void
 *
 * @uses AppController::_generateTitle()
 */
	public function generateTitle() {
		parent::_generateTitle();
	}

/**
 * Método auxiliar para establecer la configuración de notificaciones
 *
 * @param array $notify Configuración de notificaciones
 *
 * @return void
 *
 * @see AppController::$_notify
 * @see AppController::_notify()
 */
	public function setNotify($notify) {
		$this->_notify = $notify;
	}

/**
 * Genera una notificación
 *
 * @param string|Exception $name
 * @param array $config
 *
 * @return void
 *
 * @uses AppController::_notify()
 */
	public function notify($name = null, $config = array()) {
		parent::_notify($name, $config);
	}

/**
 * Índice
 *
 * @return void
 */
	public function index() {
		$this->render(false);
	}

/**
 * Índice
 *
 * @return void
 */
	public function admin_index() {
		$this->render(false);
	}

/**
 * Agregar
 *
 * @return void
 */
	public function add() {
		$this->render(false);
	}

/**
 * Editar
 *
 * @return void
 */
	public function admin_edit() {
		$this->render(false);
	}

}


/**
 * AppControllerTest
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class AppControllerTest extends ControllerTestCase {

/**
 * Tests
 *
 * @var mixed
 */
	protected $_Tests;

/**
 * Constructor
 *
 * @param mixed $name
 * @param mixed $data
 * @param mixed $dataName
 *
 * @return void
 */
	public function __construct($name = null, $data = array(), $dataName = '') {
		parent::__construct($name, $data, $dataName);

		$this->_Tests = $this->generate('Tests', array(
			'components' => array('Session'),
			'methods' => array('redirect')
		));

		if ($this->_Tests->Components->enabled('Toolbar')) {
			$this->_Tests->Components->disable('Toolbar');
		}
	}

/**
 * testCacheHeaders
 *
 * @return void
 */
	public function testCacheHeaders() {
		$this->testAction('tests', array('method' => 'GET'));

		$this->assertNotEmpty($this->headers);
		$this->assertTrue(in_array('no-store, no-cache, must-revalidate, post-check=0, pre-check=0', $this->headers));
	}

/**
 * testRedirectToPasswordReset
 *
 * @return void
 */
	public function testRedirectToPasswordReset() {
		$Tests = $this->generate('Tests');
		$Tests->Session->write('Auth.User', array(
			'id' => '1024',
			'rol_id' => '2',
			'legajo' => '1024',
			'reset' => 1
		));

		$this->testAction('tests/index', array('method' => 'GET'));
		$this->assertContains('restablecer', $this->headers['Location']);

		$Tests->Session->delete('Auth');
	}
}
