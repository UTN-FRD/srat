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
 * Reporte
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class Reporte extends AppModel {

/**
 * Tabla
 *
 * @var bool|string
 */
	public $useTable = false;

/**
 * Esquema
 *
 * @var array
 */
	protected $_schema = array(
		'asignatura_id' => array(
			'default' => null,
			'length' => 10,
			'null' => false,
			'type' => 'integer'
		),
		'carrera_id' => array(
			'default' => null,
			'length' => 10,
			'null' => false,
			'type' => 'integer'
		),
		'usuario_id' => array(
			'default' => null,
			'length' => 10,
			'null' => false,
			'type' => 'integer'
		),
		'desde' => array(
			'default' => null,
			'length' => null,
			'null' => false,
			'type' => 'date'
		),
		'hasta' => array(
			'default' => null,
			'length' => null,
			'null' => false,
			'type' => 'date'
		)
	);

/**
 * hasOne
 *
 * @var array
 */
	public $hasOne = array(
		'Asignatura' => array(
			'foreignKey' => false
		),
		'Carrera' => array(
			'className' => 'AsignaturasCarrera',
			'foreignKey' => false
		),
		'Usuario' => array(
			'foreignKey' => false
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
				'allowEmpty' => true,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Asignatura'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'carrera_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => true,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Carrera'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'usuario_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => true,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Usuario'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'desde' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => true,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'valid' => array(
				'rule' => array('date', 'ymd'),
				'message' => 'La fecha seleccionada no es válida'
			)
		),
		'hasta' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => true,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'valid' => array(
				'rule' => array('date', 'ymd'),
				'message' => 'La fecha seleccionada no es válida'
			),
			'validEndDate' => array(
				'rule' => 'validateEndDate',
				'message' => 'La fecha seleccionada debe ser igual o mayor que la indicada en el campo previo'
			)
		)
	);
}
