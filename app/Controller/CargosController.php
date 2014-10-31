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
 * Cargos
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class CargosController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array(
				'id', 'asignatura', 'usuario', 'dedicacion', 'resolucion',
				'Tipo.nombre', 'Dedicacion.nombre', 'Grado.nombre'
			),
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
		$this->Cargo->virtualFields = array(
			'asignatura' => $this->Cargo->Asignatura->virtualFields['asignatura'],
			'usuario' => $this->Cargo->Usuario->virtualFields['nombre_completo']
		);

		$this->Cargo->bindModel(array(
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
			'conditions' => $this->Cargo->parseCriteria($this->Prg->parsedParams())
		);

		$this->set(array(
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Usuarios - Asignaturas',
			'title_for_view' => 'Usuarios'
		));
	}

/**
 * Agregar
 *
 * @return void
 */
	public function admin_agregar() {
		if ($this->request->is('post')) {
			if ($this->Cargo->save($this->request->data)) {
				$this->_notify('record_created');
			} elseif (empty($this->Cargo->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		$this->__setFormData();
		$this->set(array(
			'title_for_layout' => 'Agregar - Usuarios - Asignaturas',
			'title_for_view' => 'Agregar usuario'
		));
	}

/**
 * Editar
 *
 * @param integer|null $id Identificador
 *
 * @return void
 *
 * @throws NotFoundException Si el registro no existe
 */
	public function admin_editar($id = null) {
		$this->Cargo->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->Cargo->exists()) {
			throw new NotFoundException;
		}

		if ($this->request->is('put')) {
			if ($this->Cargo->save($this->request->data)) {
				$this->_notify('record_modified');
			} elseif (empty($this->Cargo->validationErrors)) {
				$this->_notify('record_not_saved');
			}
		}

		if (!$this->request->data) {
			$this->request->data = $this->Cargo->read(array(
				'id', 'asignatura_id', 'usuario_id', 'tipo_id', 'dedicacion_id', 'grado_id', 'dedicacion', 'resolucion'
			));

			if (!empty($this->request->data['Cargo']['dedicacion'])) {
				$this->request->data['Cargo']['dedicacion'] = round($this->request->data['Cargo']['dedicacion'], 1);
			}
		}

		$this->__setFormData();
		$this->set(array(
			'title_for_layout' => 'Editar - Usuarios - Asignaturas',
			'title_for_view' => 'Editar usuario'
		));
	}

/**
 * Eliminar
 *
 * @param integer|null $id Identificador
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

		$this->Cargo->id = $id;
		if (!filter_var($id, FILTER_VALIDATE_INT) || !$this->Cargo->exists()) {
			throw new NotFoundException;
		}

		$notify = 'record_not_deleted';
		if ($this->Cargo->delete()) {
			$notify = 'record_deleted';
		}
		$this->_notify($notify);
	}

/**
 * Método auxiliar utilizado por las acciones agregar y editar
 *
 * @return void
 */
	private function __setFormData() {
		$this->set(array(
			'asignaturas' => $this->Cargo->Asignatura->find('list', array(
				'order' => array('Materia.nombre' => 'asc'),
				'recursive' => 0
			)),
			'usuarios' => $this->Cargo->Usuario->find('list', array(
				'order' => array('Usuario.nombre' => 'asc')
			)),
			'tipos' => $this->Cargo->Tipo->find('list', array('order' => array('id' => 'asc'))),
			'dedicaciones' => $this->Cargo->Dedicacion->find('list', array('order' => array('id' => 'asc'))),
			'grados' => $this->Cargo->Grado->find('list', array('order' => array('id' => 'asc')))
		));
	}
}
