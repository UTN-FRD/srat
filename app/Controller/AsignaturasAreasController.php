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
 * AsignaturasAreas
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class AsignaturasAreasController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array('id', 'nombre', 'obs'),
			'limit' => 15,
			'maxLimit' => 15,
			'order' => array('nombre' => 'asc')
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
			'conditions' => $this->AsignaturasArea->parseCriteria($this->Prg->parsedParams())
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Áreas - Administrar',
			'title_for_view' => 'Áreas'
		));
	}

/**
 * Agregar
 *
 * @return void
 */
	public function admin_agregar() {
		if ($this->request->is('post')) {
			if ($this->AsignaturasArea->save($this->request->data)) {
				$this->_notify('record_created');
			} elseif (empty($this->AsignaturasArea->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		$this->set(array(
			'title_for_layout' => 'Agregar - Áreas - Administrar',
			'title_for_view' => 'Agregar área'
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
		$this->AsignaturasArea->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->AsignaturasArea->exists()) {
			throw new NotFoundException;
		}

		if ($this->request->is('put')) {
			if ($this->AsignaturasArea->save($this->request->data)) {
				$this->_notify('record_modified');
			} elseif (empty($this->AsignaturasArea->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->AsignaturasArea->read(array('id', 'nombre', 'obs'));
		}

		$this->set(array(
			'associated' => $this->AsignaturasArea->hasAssociations(),
			'title_for_layout' => 'Editar - Áreas - Administrar',
			'title_for_view' => 'Editar área'
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

		$this->AsignaturasArea->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->AsignaturasArea->exists()) {
			throw new NotFoundException;
		}

		$notify = 'record_not_deleted';
		if ($this->AsignaturasArea->hasAssociations()) {
			$notify = 'record_delete_associated';
		} else {
			if ($this->AsignaturasArea->delete()) {
				$notify = 'record_deleted';
			}
		}
		$this->_notify($notify);
	}
}
