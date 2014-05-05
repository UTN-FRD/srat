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
App::uses('AppModel', 'Model');

/**
 * Cargo
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class Cargo extends AppModel {

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
		'Asignatura',
		'Usuario',
		'Dedicacion' => array(
			'className' => 'CargosDedicacion',
			'foreignKey' => 'dedicacion_id'
		),
		'Grado' => array(
			'className' => 'CargosGrado',
			'foreignKey' => 'grado_id'
		),
		'Tipo' => array(
			'className' => 'CargosTipo',
			'foreignKey' => 'tipo_id'
		)
	);

/**
 * Campos de búsqueda
 *
 * @var array
 */
	public $filterArgs = array(
		'buscar' => array(
			'field' => array(
				'Carrera.nombre', 'Materia.nombre', 'Usuario.apellido', 'Usuario.nombre',
				'CONCAT(Usuario.nombre, " ", Usuario.apellido)'
			),
			'type' => 'like'
		)
	);

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Asistencia'
	);

/**
 * Reglas de validación
 *
 * @var array
 */
	public $validate = array(
		'asignatura_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
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
		'usuario_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Usuario'),
				'last' => true,
				'message' => 'El valor seleccionado no existe'
			),
			'isUnique' => array(
				'rule' => array('validateUnique', array('asignatura_id')),
				'message' => 'El usuario seleccionado ya se encuentra asociado a la asignatura seleccionada'
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
		),
		'dedicacion_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Dedicacion'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'grado_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Grado'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'dedicacion' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'inList' => array(
				'rule' => array('inList', array('0', '0.5', '0,5', '1', '1.5', '1,5', '2')),
				'message' => 'El valor ingresado no es válido'
			)
		),
		'resolucion' => array(
			'rule' => array('range', 0, 65536),
			'required' => true,
			'allowEmpty' => true,
			'message' => 'El valor ingresado no es válido'
		)
	);

/**
 * beforeSave
 *
 * @param array $options Opciones
 *
 * @return boolean `true` para continuar la operación de guardado o `false` para cancelarla
 */
	public function beforeSave($options = array()) {
		if (!empty($this->data[$this->alias]['dedicacion'])) {
			$this->data[$this->alias]['dedicacion'] = str_replace(',', '.', $this->data[$this->alias]['dedicacion']);
		}
		return true;
	}
}
