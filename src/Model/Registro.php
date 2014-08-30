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
 * Registro
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class Registro extends AppModel {

/**
 * belongsTo
 *
 * @var array
 */
	public $belongsTo = array(
		'Cargo'
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
		'cargo_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Cargo'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'obs' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
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
			'notEmpty' => array(
				'rule' => 'notEmpty',
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
			'notEmpty' => array(
				'rule' => 'notEmpty',
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
 * beforeValidate
 *
 * @param array $options Opciones
 *
 * @return boolean `true` para continuar la operación de validación o `false` para cancelarla
 */
	public function beforeValidate($options = array()) {
		if (!isset($this->data[$this->alias]['id'])) {
			$skip = true;
			foreach (array('entrada', 'salida', 'obs') as $field) {
				if (!empty($this->data[$this->alias][$field])) {
					$skip = false;
					break;
				}
			}
			if ($skip) {
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
 * @return boolean `true` en caso exitoso o `false` en caso contrario
 */
	public function validateEndTime($check) {
		if (!empty($this->data[$this->alias]['entrada']) && !empty($this->data[$this->alias]['salida'])) {
			$startTime = (int)strtotime($this->data[$this->alias]['entrada']);
			$endTime = (int)strtotime($this->data[$this->alias]['salida']);

			return ($startTime < $endTime);
		}
		return false;
	}
}