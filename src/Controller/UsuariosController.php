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
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (!$this->Auth->loggedIn()) {
			if (in_array(strtolower($this->request->action), array('dashboard', 'logout'))) {
				$this->Auth->authError = false;
			}
			$this->Auth->allow('login');
		}
	}

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
		if ($this->Auth->loggedIn()) {
			$this->redirect($this->Auth->loginRedirect);
		}

		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->redirect($this->Auth->redirectUrl());
			}

			$this->request->data['Usuario']['password'] = null;
			$this->Auth->flash('Los datos ingresados no son correctos.');
		}

		$this->set('title_for_layout', 'Inicio de sesión');
	}

/**
 * Cierre de sesión
 *
 * @return void
 */
	public function logout() {
		$this->redirect($this->Auth->logout());
	}
}
