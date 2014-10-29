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

/**
 * testGenerateTitleForIndex
 *
 * @return void
 */
	public function testGenerateTitleForIndex() {
		$this->testAction('tests', array('method' => 'GET'));

		$this->assertEquals($this->_Tests->viewVars, array(
			'title_for_layout' => 'Tests',
			'title_for_view' => 'Tests'
		));
	}

/**
 * testGenerateTitleForIndexWithPrefix
 *
 * @return void
 */
	public function testGenerateTitleForIndexWithPrefix() {
		$this->testAction('admin/tests/index', array('method' => 'GET'));

		$this->assertEquals($this->_Tests->viewVars, array(
			'title_for_layout' => 'Tests',
			'title_for_view' => 'Tests'
		));
	}

/**
 * testGenerateTitleForNonIndex
 *
 * @return void
 */
	public function testGenerateTitleForNonIndex() {
		$this->testAction('tests/add', array('method' => 'GET'));

		$this->assertEquals($this->_Tests->viewVars, array(
			'title_for_layout' => 'Add - Tests',
			'title_for_view' => 'Add test'
		));
	}

/**
 * testGenerateTitleForNonIndexWithPrefix
 *
 * @return void
 */
	public function testGenerateTitleForNonIndexWithPrefix() {
		$this->testAction('admin/tests/edit', array('method' => 'GET'));

		$this->assertEquals($this->_Tests->viewVars, array(
			'title_for_layout' => 'Edit - Tests',
			'title_for_view' => 'Edit test'
		));
	}

/**
 * testIsAuthorized
 *
 * @return void
 */
	public function testIsAuthorized() {
		$this->assertTrue(
			$this->_Tests->isAuthorized()
		);
	}

/**
 * testIsAuthorizedAdmin
 *
 * @return void
 */
	public function testIsAuthorizedAdmin() {
		$this->testAction('admin/tests/index', array('method' => 'GET'));

		$this->assertTrue(
			$this->_Tests->isAuthorized(array('rol_id' => 1))
		);
	}

/**
 * testIsNotAuthorizedNonAdmin
 *
 * @return void
 */
	public function testIsNotAuthorizedNonAdmin() {
		$this->testAction('admin/tests/index', array('method' => 'GET'));

		$this->assertFalse(
			$this->_Tests->isAuthorized(array('rol_id' => 2))
		);
	}

/**
 * testBlackHoleCallBack
 *
 * @return void
 */
	public function testBlackHoleCallBack() {
		$this->_Tests->setNotify(array(
			'blackHole' => array(
				'level' => 'error',
				'message' => 'The request has been black-holed',
				'redirect' => true
			)
		));

		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with('The request has been black-holed', 'notify', array('level' => 'error'));

		$this->_Tests->expects($this->once())
			->method('redirect')
			->with('');

		$this->_Tests->blackHole();
	}

/**
 * testNotifyDefault
 *
 * @return void
 */
	public function testNotifyDefault() {
		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with('No hay descripción del error.', 'notify', array('level' => 'error'));

		$this->_Tests->notify();
	}

/**
 * testNotifyMessage
 *
 * @return void
 */
	public function testNotifyMessage() {
		$this->_Tests->setNotify(array(
			'test' => array(
				'message' => 'The quick brown fox jumps over the lazy dog'
			)
		));

		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with('The quick brown fox jumps over the lazy dog', 'notify', $this->anything());

		$this->_Tests->notify('test');
	}

/**
 * testNotifyOverrideMessage
 *
 * @return void
 */
	public function testNotifyOverrideMessage() {
		$this->_Tests->setNotify(array(
			'test' => array(
				'message' => 'The quick brown fox jumps over the lazy dog'
			)
		));

		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with('The quick brown dog jumps over the lazy cat', 'notify', $this->anything());

		$this->_Tests->notify('test', array('message' => 'The quick brown dog jumps over the lazy cat'));
	}

/**
 * testNotifyLevel
 *
 * @return void
 */
	public function testNotifyLevel() {
		$this->_Tests->setNotify(array(
			'test' => array(
				'level' => 'warning'
			)
		));

		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with($this->anything(), 'notify', array('level' => 'warning'));

		$this->_Tests->notify('test');
	}

/**
 * testNotifyOverrideLevel
 *
 * @return void
 */
	public function testNotifyOverrideLevel() {
		$this->_Tests->setNotify(array(
			'test' => array(
				'level' => 'success'
			)
		));

		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with($this->anything(), 'notify', array('level' => 'info'));

		$this->_Tests->notify('test', array('level' => 'info'));
	}

/**
 * testNotifyRedirect
 *
 * @return void
 */
	public function testNotifyRedirect() {
		$this->_Tests->setNotify(array(
			'test' => array(
				'redirect' => array('controller' => 'tests', 'action' => 'index')
			)
		));

		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with($this->anything(), 'notify', $this->anything());

		$this->_Tests->expects($this->once())
			->method('redirect')
			->with(array('controller' => 'tests', 'action' => 'index'));

		$this->_Tests->notify('test');
	}

/**
 * testNotifyRedirectRefresh
 *
 * @return void
 */
	public function testNotifyRedirectRefresh() {
		$this->_Tests->setNotify(array(
			'test' => array(
				'redirect' => true
			)
		));

		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with($this->anything(), 'notify', $this->anything());

		$this->_Tests->expects($this->once())
			->method('redirect')
			->with('');

		$this->_Tests->notify('test');
	}

/**
 * testNotifyOverrideRedirect
 *
 * @return void
 */
	public function testNotifyOverrideRedirect() {
		$this->_Tests->setNotify(array(
			'test' => array(
				'redirect' => true
			)
		));

		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with($this->anything(), 'notify', $this->anything());

		$this->_Tests->expects($this->never())
			->method('redirect');

		$this->_Tests->notify('test', array('redirect' => false));
	}

/**
 * testNotifyException
 *
 * @return void
 *
 * @throws NotFoundException
 */
	public function testNotifyException() {
		$this->_Tests->setNotify(array(
			'not_found' => array(
				'level' => 'warning',
				'message' => null,
				'redirect' => false
			)
		));

		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with('Not Found', 'notify', array('level' => 'warning'));

		try {
			throw new NotFoundException;
		} catch (NotFoundException $e) {
			$this->_Tests->notify($e);
		}
	}

/**
 * testNotifyExceptionCustomMessage
 *
 * @return void
 *
 * @throws NotFoundException
 */
	public function testNotifyExceptionCustomMessage() {
		$this->_Tests->setNotify(array(
			'not_found' => array(
				'level' => 'error',
				'message' => 'File not found',
				'redirect' => false
			)
		));

		$this->_Tests->Session->expects($this->once())
			->method('setFlash')
			->with('File not found', 'notify', array('level' => 'error'));

		try {
			throw new NotFoundException;
		} catch (NotFoundException $e) {
			$this->_Tests->notify($e);
		}
	}
}
