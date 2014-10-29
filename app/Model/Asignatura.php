<?php
/**
 * Sistema de Registro de Asistencia y Temas
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
App::uses('AppModel', 'Model');

/**
 * Asignatura
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class Asignatura extends AppModel {

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
		'Area' => array(
			'className' => 'AsignaturasArea',
			'foreignKey' => 'area_id'
		),
		'Carrera' => array(
			'className' => 'AsignaturasCarrera',
			'foreignKey' => 'carrera_id'
		),
		'Materia' => array(
			'className' => 'AsignaturasMateria',
			'foreignKey' => 'materia_id'
		),
		'Nivel' => array(
			'className' => 'AsignaturasNivel',
			'foreignKey' => 'nivel_id'
		),
		'Tipo' => array(
			'className' => 'AsignaturasTipo',
			'foreignKey' => 'tipo_id'
		)
	);

/**
 * Nombre del campo utilizado
 * por el tipo de búsqueda `list`
 *
 * @var string
 */
	public $displayField = 'asignatura';

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
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Cargo',
		'Horario'
	);

/**
 * Reglas de validación
 *
 * @var array
 */
	public $validate = array(
		'carrera_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Carrera'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'materia_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Materia'),
				'last' => true,
				'message' => 'El valor seleccionado no existe'
			),
			'isUnique' => array(
				'rule' => array('validateUnique', array('carrera_id')),
				'message' => 'La materia seleccionada ya se encuentra asociada a la carrera seleccionada'
			)
		),
		'area_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Area'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'nivel_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Nivel'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'tipo_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Tipo'),
				'message' => 'El valor seleccionado no existe'
			)
		)
	);

/**
 * Campos virtuales
 *
 * @var array
 */
	public $virtualFields = array(
		'asignatura' => 'CONCAT(Carrera.nombre, ": ", Materia.nombre)'
	);
}
