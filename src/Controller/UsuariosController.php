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
App::uses('AppController', 'Controller');

/**
 * Usuarios
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class UsuariosController extends AppController {

/**
 * Dashboard
 *
 * @return void
 */
	public function dashboard() {
		$this->set('title_for_layout', 'Dashboard');
	}

/**
 * Inicio de sesión
 *
 * @return void
 */
	public function login() {
		$this->set('title_for_layout', 'Inicio de sesión');
	}

/**
 * Cierre de sesión
 *
 * @return void
 */
	public function logout() {
		$this->autoRender = false;
	}
}
