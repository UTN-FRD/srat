<?php
/**
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo LICENCIA.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

/**
 * Dependencias
 */
App::uses('AppController', 'Controller');

/**
 * Inasistencias
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class InasistenciasController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array('id', 'asignatura', 'Usuario.legajo', 'docente', 'fecha', 'obs'),
			'limit' => 15,
			'maxLimit' => 15,
			'order' => array('fecha' => 'desc'),
			'recursive' => 0
		)
	);

/**
 * Índice
 *
 * @return void
 */
	public function admin_index() {
		$this->Prg->commonProcess();
		$this->Paginator->settings += array(
			'conditions' => $this->Inasistencia->parseCriteria($this->Prg->parsedParams())
		);

		$this->set('rows', $this->Paginator->paginate());
	}

/**
 * Editar
 *
 * @return void
 *
 * @throws MethodNotAllowedException Si la petición no se realiza utilizando el método POST o no se reciben datos
 */
	public function admin_editar() {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException;
		}

		if (isset($this->request->data['Inasistencia'][0])) {
			if ($this->Inasistencia->saveMany($this->request->data['Inasistencia'], array('fieldList' => array('obs')))) {
				$this->_notify('record_modified');
			} elseif (empty($this->Inasistencia->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		} elseif (!empty($this->request->data['Inasistencia']['id'])) {
			$max = max($this->request->data['Inasistencia']['id']);
			if (!$max) {
				$this->redirect(array('action' => 'index'));
			}

			$ids = array();
			foreach ($this->request->data['Inasistencia']['id'] as $id => $checked) {
				if ((bool)$checked) {
					$ids[] = $id;
				}
			}
			$this->request->data['Inasistencia'] = array();

			$rows = $this->Inasistencia->find('all', array(
				'conditions' => array('Inasistencia.id' => $ids),
				'fields' => array('id', 'asignatura', 'docente', 'fecha', 'obs'),
				'recursive' => 0
			));
			$this->request->data['Inasistencia'] = Hash::extract($rows, '{n}.Inasistencia');
		}

		$this->set(array(
			'title_for_layout' => 'Editar - Inasistencias',
			'title_for_view' => 'Editar registro(s)'
		));
	}
}
