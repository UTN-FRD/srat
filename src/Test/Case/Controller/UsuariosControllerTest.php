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

/**
 * testAccessToRestrictedAction
 *
 * @return void
 */
	public function testAccessToRestrictedAction() {
		$Usuarios = $this->generate('Usuarios');

		$this->testAction('usuarios/dashboard', array('method' => 'GET'));
		$this->assertEmpty($Usuarios->Session->read('Message.auth'));
		$this->assertNotEmpty($Usuarios->Session->read('Auth.redirect'));

		$this->testAction('usuarios/perfil', array('method' => 'GET'));
		$this->assertNotEmpty($Usuarios->Session->read('Message.auth'));
		$this->assertNotEmpty($Usuarios->Session->read('Auth.redirect'));

		$Usuarios->Session->write('Auth.User', current(
			$Usuarios->Usuario->read(null, 1)
		));
		$result = $this->testAction('usuarios/perfil', array('method' => 'GET', 'return' => 'contents'));
		$this->assertEquals($this->vars['title_for_layout'], 'Perfil');
		$this->assertContains('id="UsuarioPerfilForm"', $result);
	}

/**
 * testAccessToResetPasswordAction
 *
 * @return void
 */
	public function testAccessToResetPasswordAction() {
		$Usuarios = $this->generate('Usuarios');

		$this->testAction('usuarios/restablecer', array('method' => 'GET'));
		$this->assertNotEmpty($Usuarios->Session->read('Message.auth'));
		$this->assertNotEmpty($Usuarios->Session->read('Auth.redirect'));

		$Usuarios->Session->write('Auth.User', current(
			$Usuarios->Usuario->read(null, 1)
		));
		$this->testAction('usuarios/restablecer', array('method' => 'GET'));
		$this->assertNotEmpty($this->headers['Location']);

		$Usuarios->Session->write('Auth.User', current(
			$Usuarios->Usuario->read(null, 4)
		));
		$result = $this->testAction('usuarios/restablecer', array('method' => 'GET', 'return' => 'contents'));
		$this->assertEquals('Restablecer contraseña', $this->vars['title_for_layout']);
		$this->assertContains('id="UsuarioRestablecerForm"', $result);
	}
}
