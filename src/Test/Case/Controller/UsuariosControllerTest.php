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
App::uses('CakeSession', 'Model/Datasource');
App::uses('UsuariosController', 'Controller');

/**
 * UsuariosControllerTest
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class UsuariosControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.usuario',
		'app.usuarios_rol'
	);

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->__clearSession();
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();

		$this->__clearSession();
	}

/**
 * __clearSession
 *
 * @return void
 */
	private function __clearSession() {
		foreach (array('Auth', 'Message') as $key) {
			if (CakeSession::check($key)) {
				CakeSession::delete($key);
			}
		}
	}

/**
 * testAccessToPublicAction
 *
 * @return void
 */
	public function testAccessToPublicAction() {
		$Usuarios = $this->generate('Usuarios');

		$this->testAction('usuarios/docentes', array('method' => 'GET'));
		$this->assertEquals($this->vars['title_for_layout'], 'Docentes');
		$this->assertNotEmpty($this->vars['rows']);

		$user = current($Usuarios->Usuario->read(null, 1));
		$Usuarios->Session->write('Auth.User', $user);
		$result = $this->testAction('usuarios/docentes', array('method' => 'GET', 'return' => 'contents'));
		$this->assertContains($user['nombre_completo'], $result);
		$this->assertEquals($this->vars['title_for_layout'], 'Docentes');
		$this->assertNotEmpty($this->vars['rows']);
	}
}
