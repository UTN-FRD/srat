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
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'limit' => 15,
			'maxLimit' => 15,
			'order' => array('legajo' => 'asc')
		)
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		if (!$this->Auth->user()) {
			if (in_array($this->request->action, array('dashboard', 'logout'))) {
				$this->Auth->authError = false;
			}
			$this->Auth->allow('docentes', 'login');
		}
	}

/**
 * Dashboard
 *
 * @return void
 */
	public function dashboard() {
		$this->set(array(
			'title_for_layout' => 'Dashboard',
			'title_for_view' => 'Dashboard'
		));
	}

/**
 * Inicio de sesión
 *
 * @return void
 */
	public function login() {
		if ($this->Auth->user()) {
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

/**
 * Docentes
 *
 * @return void
 */
	public function docentes() {
		$settings = array(
			'conditions' => array('rol_id' => 2),
			'fields' => array('apellido', 'legajo', 'nombre'),
			'recursive' => -1
		);
		$this->Prg->commonProcess();
		$settings['conditions'] += $this->Usuario->parseCriteria($this->Prg->parsedParams());
		$this->Paginator->settings += $settings;

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Docentes',
			'title_for_view' => 'Docentes'
		));
	}

/**
 * Índice
 *
 * @return void
 */
	public function admin_index() {
		$this->Prg->commonProcess();
		$this->Paginator->settings += array(
			'conditions' => $this->Usuario->parseCriteria($this->Prg->parsedParams()),
			'fields' => array('id', 'apellido', 'estado', 'legajo', 'nombre', 'Rol.nombre'),
			'recursive' => 0
		);

		$this->set('rows', $this->Paginator->paginate());
	}
}
