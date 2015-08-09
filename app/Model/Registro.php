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
 * Registro
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class Registro extends AppModel {

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
		'Usuario'
	);

/**
 * Campos de búsqueda
 *
 * @var array
 */
	public $filterArgs = array(
		'buscar' => array(
			'method' => 'orConditions',
			'type' => 'query'
		)
	);

/**
 * Reglas de validación
 *
 * @var array
 */
	public $validate = array(
		'tipo' => array(
			'rule' => array('inList', array('0', '1')),
			'message' => 'El valor seleccionado no es válido'
		),
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
		'usuario_id' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Usuario'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'obs' => array(
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 65535),
				'message' => 'El valor ingresado es demasiado grande'
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
 * Campos virtuales
 *
 * @var array
 */
	public $virtualFields = array(
		'solo_fecha' => 'DATE(fecha)'
	);

/**
 * beforeValidate
 *
 * @param array $options Opciones
 *
 * @return bool `true` para continuar la operación de validación o `false` para cancelarla
 */
	public function beforeValidate($options = array()) {
		if (!isset($this->data[$this->alias]['id'])) {
			if (empty($this->data[$this->alias]['obs'])) {
				$this->data = $this->validate = array();
			}
		}

		return true;
	}

/**
 * Valida que la hora de salida sea mayor que la hora de entrada
 *
 * @param array $check Nombre del campo y su valor
 *
 * @return bool `true` en caso exitoso o `false` en caso contrario
 */
	public function validateEndTime($check) {
		if (!empty($this->data[$this->alias]['entrada']) && !empty($this->data[$this->alias]['salida'])) {
			$startTime = (int)strtotime($this->data[$this->alias]['entrada']);
			$endTime = (int)strtotime($this->data[$this->alias]['salida']);

			return ($startTime < $endTime);
		}
		return false;
	}

/**
 * Genera condiciones de búsqueda
 *
 * @param array $data Datos a buscar
 *
 * @return array
 */
	public function orConditions($data = array()) {
		$value = current($data);
		$condition = array(
			'OR' => array(
				'Carrera.nombre LIKE' => '%' . $value . '%',
				'Materia.nombre LIKE' => '%' . $value . '%',
				'Usuario.apellido LIKE' => '%' . $value . '%',
				'Usuario.nombre LIKE' => '%' . $value . '%'
			)
		);

		if (preg_match('/^[0-9\/]+$/', $value)) {
			$date = date_create_from_format('d/m/Y', $value);
			if ($date) {
				$condition['OR']['Registro.fecha LIKE'] = '%' . $date->format('Y-m-d') . '%';
			} else {
				$condition['OR']['Registro.fecha LIKE'] = '%' . $value . '%';
			}
		}

		return $condition;
	}
}
