<?php
/**
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

/**
 * Dependencias
 */
App::uses('AppController', 'Controller');

/**
 * Asignaturas
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class AsignaturasController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array('id', 'asignatura', 'Area.nombre', 'Nivel.nombre', 'Tipo.nombre'),
			'limit' => 15,
			'maxLimit' => 15,
			'order' => array('Materia.nombre' => 'asc'),
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
			'conditions' => $this->Asignatura->parseCriteria($this->Prg->parsedParams())
		);

		$this->set('rows', $this->Paginator->paginate());
	}

/**
 * Agregar
 *
 * @return void
 */
	public function admin_agregar() {
		if ($this->request->is('post')) {
			if ($this->Asignatura->save($this->request->data)) {
				$this->_notify('record_created');
			} elseif (empty($this->Asignatura->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		$this->set(array(
			'areas' => $this->Asignatura->Area->find('list', array('order' => array('nombre' => 'asc'))),
			'carreras' => $this->Asignatura->Carrera->find('list', array('order' => array('nombre' => 'asc'))),
			'materias' => $this->Asignatura->Materia->find('list', array('order' => array('nombre' => 'asc'))),
			'niveles' => $this->Asignatura->Nivel->find('list', array('order' => array('id' => 'asc'))),
			'tipos' => $this->Asignatura->Tipo->find('list', array('order' => array('nombre' => 'asc')))
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
		$this->Asignatura->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->Asignatura->exists()) {
			throw new NotFoundException;
		}

		if ($this->request->is('put')) {
			if ($this->Asignatura->save($this->request->data)) {
				$this->_notify('record_modified');
			} elseif (empty($this->Asignatura->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->Asignatura->read(
				array('id', 'area_id', 'carrera_id', 'materia_id', 'nivel_id', 'tipo_id')
			);
		}

		$this->set(array(
			'associated' => $this->Asignatura->hasAssociations(),
			'areas' => $this->Asignatura->Area->find('list', array('order' => array('nombre' => 'asc'))),
			'carreras' => $this->Asignatura->Carrera->find('list', array('order' => array('nombre' => 'asc'))),
			'materias' => $this->Asignatura->Materia->find('list', array('order' => array('nombre' => 'asc'))),
			'niveles' => $this->Asignatura->Nivel->find('list', array('order' => array('id' => 'asc'))),
			'tipos' => $this->Asignatura->Tipo->find('list', array('order' => array('nombre' => 'asc')))
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

		$this->Asignatura->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->Asignatura->exists()) {
			throw new NotFoundException;
		}

		$notify = 'record_not_deleted';
		if ($this->Asignatura->hasAssociations()) {
			$notify = 'record_delete_associated';
		} else {
			if ($this->Asignatura->delete()) {
				$notify = 'record_deleted';
			}
		}
		$this->_notify($notify);
	}
}
