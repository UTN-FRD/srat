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
 * Asistencias
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class AsistenciasController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Search.Prg',
		'Paginator' => array(
			'fields' => array('asignatura', 'usuario', 'fecha', 'entrada', 'salida', 'obs'),
			'limit' => 15,
			'maxLimit' => 15,
			'order' => array('Asistencia.fecha' => 'desc'),
			'recursive' => 0
		)
	);

/**
 * Índice
 *
 * @return void
 */
	public function admin_index() {
		$this->__setupModelAssociations();

		$this->Prg->commonProcess();
		$this->Paginator->settings += array(
			'conditions' => $this->Asistencia->parseCriteria($this->Prg->parsedParams())
		);

		$this->set('rows', $this->Paginator->paginate());
	}

/**
 * Establece asociaciones entre modelos necesarias para realizar búsquedas
 *
 * @return void
 */
	private function __setupModelAssociations() {
		$this->Asistencia->virtualFields = array(
			'asignatura' => $this->Asistencia->Cargo->Asignatura->virtualFields['asignatura'],
			'usuario' => $this->Asistencia->Cargo->Usuario->virtualFields['nombre_completo']
		);

		$this->Asistencia->bindModel(array(
			'hasOne' => array(
				'Asignatura' => array(
					'className' => 'Asignatura',
					'conditions' => 'Asignatura.id = Cargo.asignatura_id',
					'foreignKey' => false
				),
				'Carrera' => array(
					'className' => 'AsignaturasCarrera',
					'conditions' => 'Carrera.id = Asignatura.carrera_id',
					'foreignKey' => false
				),
				'Materia' => array(
					'className' => 'AsignaturasMateria',
					'conditions' => 'Materia.id = Asignatura.materia_id',
					'foreignKey' => false
				),
				'Usuario' => array(
					'className' => 'Usuario',
					'conditions' => 'Usuario.id = Cargo.usuario_id',
					'foreignKey' => false
				)
			)
		), false);
	}
}
