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
App::uses('Hash', 'Utility');

/**
 * Períodos
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class PeriodosController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array('id', 'desde', 'hasta', 'obs'),
			'limit' => 15,
			'maxLimit' => 15,
			'order' => array('desde' => 'asc'),
			'recursive' => -1
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
			'conditions' => $this->Periodo->parseCriteria($this->Prg->parsedParams())
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Períodos no laborables - Administrar',
			'title_for_view' => 'Períodos no laborables'
		));
	}

/**
 * Agregar
 *
 * @return void
 */
	public function admin_agregar() {
		if ($this->request->is('post')) {
			if ($this->Periodo->save($this->request->data)) {
				$this->_notify('record_created');
			} elseif (empty($this->Periodo->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		$this->set(array(
			'title_for_layout' => 'Agregar - Períodos no laborables - Administrar',
			'title_for_view' => 'Agregar período no laborable'
		));
	}

/**
 * Editar
 *
 * @param int|null $id Identificador
 *
 * @return void
 *
 * @throws NotFoundException Si el registro no existe
 */
	public function admin_editar($id = null) {
		$this->Periodo->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->Periodo->exists()) {
			throw new NotFoundException;
		}

		if ($this->request->is('put')) {
			if ($this->Periodo->save($this->request->data)) {
				$this->_notify('record_modified');
			} elseif (empty($this->Periodo->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->Periodo->read(array('id', 'desde', 'hasta', 'obs'));
		}

		$this->set(array(
			'title_for_layout' => 'Editar - Períodos no laborables - Administrar',
			'title_for_view' => 'Editar período no laborable'
		));
	}

/**
 * Eliminar
 *
 * @param int|null $id Identificador
 *
 * @return void
 *
 * @throws MethodNotAllowedException Si el método es diferente de DELETE
 * @throws NotFoundException Si el registro no existe o corresponde al primer usuario
 */
	public function admin_eliminar($id = null) {
		if (!$this->request->is('delete')) {
			throw new MethodNotAllowedException;
		}

		$this->Periodo->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->Periodo->exists()) {
			throw new NotFoundException;
		}

		$notify = 'record_not_deleted';
		if ($this->Periodo->delete()) {
			$notify = 'record_deleted';
		}
		$this->_notify($notify);
	}

/**
 * Exporta los períodos en formato JSON
 *
 * @return CakeResponse
 */
	public function admin_exportar() {
		$rows = $this->Periodo->find('all', array(
			'fields' => array('desde', 'hasta', 'obs'),
			'order' => array('desde' => 'ASC')
		));

		$this->response->body(json_encode(Hash::extract($rows, '{n}.Periodo')));
		$this->response->type('Content-Type: application/json');
		$this->response->download('periodos.json');

		return $this->response;
	}
}
