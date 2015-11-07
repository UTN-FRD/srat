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
 * Horarios
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class HorariosController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array('id', 'asignatura', 'dia', 'entrada', 'salida'),
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
		$this->Horario->virtualFields = array(
			'asignatura' => $this->Horario->Asignatura->virtualFields['asignatura']
		);

		$this->Horario->bindModel(array(
			'hasOne' => array(
				'Carrera' => array(
					'className' => 'AsignaturasCarrera',
					'conditions' => 'Carrera.id = Asignatura.carrera_id',
					'foreignKey' => false
				),
				'Materia' => array(
					'className' => 'AsignaturasMateria',
					'conditions' => 'Materia.id = Asignatura.materia_id',
					'foreignKey' => false
				)
			)
		), false);

		$this->Prg->commonProcess();
		$this->Paginator->settings += array(
			'conditions' => $this->Horario->parseCriteria($this->Prg->parsedParams())
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Horarios - Administrar',
			'title_for_view' => 'Horarios'
		));
	}

/**
 * Agregar
 *
 * @return void
 */
	public function admin_agregar() {
		if ($this->request->is('post')) {
			if ($this->Horario->save($this->request->data)) {
				$this->_notify('record_created');
			} elseif (empty($this->Horario->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		$this->set(array(
			'asignaturas' => $this->Horario->Asignatura->find('list', array(
				'order' => array('Materia.nombre' => 'asc'),
				'recursive' => 0
			)),
			'title_for_layout' => 'Agregar - Horarios - Administrar',
			'title_for_view' => 'Agregar horario'
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
		$this->Horario->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->Horario->exists()) {
			throw new NotFoundException;
		}

		if ($this->request->is('put')) {
			if ($this->Horario->save($this->request->data)) {
				$this->_notify('record_modified');
			} elseif (empty($this->Horario->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->Horario->read(array('id', 'asignatura_id', 'dia', 'entrada', 'salida'));
		}

		$this->set(array(
			'asignaturas' => $this->Horario->Asignatura->find('list', array(
				'order' => array('Materia.nombre' => 'asc'),
				'recursive' => 0
			)),
			'title_for_layout' => 'Editar - Horarios - Administrar',
			'title_for_view' => 'Editar horario'
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

		$this->Horario->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->Horario->exists()) {
			throw new NotFoundException;
		}

		$notify = 'record_not_deleted';
		if ($this->Horario->delete()) {
			$notify = 'record_deleted';
		}
		$this->_notify($notify);
	}
}
