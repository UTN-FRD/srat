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
App::uses('AppModel', 'Model');

/**
 * Horario
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class Horario extends AppModel {

/**
 * Comportamientos
 *
 * @var array
 */
	public $actsAs = array(
		'Search.Searchable'
	);

/**
 * belongsTo
 *
 * @var array
 */
	public $belongsTo = array(
		'Asignatura'
	);

/**
 * Campos de búsqueda
 *
 * @var array
 */
	public $filterArgs = array(
		'buscar' => array(
			'field' => array('Carrera.nombre', 'Materia.nombre'),
			'type' => 'like'
		)
	);

/**
 * Reglas de validación
 *
 * @var array
 */
	public $validate = array(
		'asignatura_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Asignatura'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'dia' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('range', -1, 7),
				'last' => true,
				'message' => 'El valor seleccionado no existe'
			),
			'isUnique' => array(
				'rule' => array('validateUnique', array('asignatura_id')),
				'message' => 'El día seleccionado ya se encuentra asociado a la asignatura seleccionada'
			)
		),
		'entrada' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'validTime' => array(
				'rule' => 'time',
				'message' => 'El valor ingresado no es válido'
			)
		),
		'salida' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'validTime' => array(
				'rule' => 'time',
				'last' => true,
				'message' => 'El valor ingresado no es válido'
			),
			'validEndTime' => array(
				'rule' => 'validateEndTime',
				'message' => 'La hora de salida debe ser mayor que la hora de entrada'
			)
		)
	);

/**
 * Devuelve los días para todos los cargos registrados agrupado por usuario y asignatura
 *
 * @return array
 */
	public function getDaysListByCargo() {
		$this->unbindModel(array(
			'belongsTo' => array('Asignatura')
		));
		$this->bindModel(array(
			'hasOne' => array(
				'Cargo' => array(
					'conditions' => 'Cargo.asignatura_id = Horario.asignatura_id',
					'fields' => array('usuario_id', 'asignatura_id'),
					'foreignKey' => false
				)
			)
		));

		$rows = $this->find('all', array(
			'conditions' => array(
				'NOT' => array('Cargo.usuario_id' => null)
			),
			'fields' => array('Cargo.asignatura_id', 'Cargo.usuario_id', 'Horario.dia'),
			'recursive' => 0
		));

		$out = array();
		foreach ($rows as $row) {
			$out[$row['Cargo']['usuario_id']][$row['Cargo']['asignatura_id']][] = $row['Horario']['dia'];
		}
		return $out;
	}
}
