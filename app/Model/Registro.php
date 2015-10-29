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
 * belongsTo
 *
 * @var array
 */
	public $belongsTo = array(
		'Asignatura',
		'Usuario'
	);

/**
 * hasOne
 *
 * @var array
 */
	public $hasOne = array(
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
		'asignatura' => 'CONCAT(Carrera.nombre, ": ", Materia.nombre)',
		'solo_fecha' => 'DATE(fecha)',
		'usuario' => 'CONCAT(Usuario.apellido, ", ", Usuario.nombre)'
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
 * Devuelve la fecha del primer registro de asistencia
 *
 * @return string|bool Fecha o `false` en caso contrario
 */
	public function getFirstPresenceDate() {
		$row = $this->find('first', array(
			'conditions' => array('tipo' => 1),
			'fields' => array('solo_fecha'),
			'order' => array('fecha' => 'asc')
		));
		return (!empty($row[$this->alias]['solo_fecha']) ? $row[$this->alias]['solo_fecha'] : false);
	}

/**
 * Devuelve una lista con la última fecha de inasistencia para todos los cargos
 * registrados agrupado por usuario y asignatura
 *
 * @return array
 */
	public function getLastAbsenseDateByCargo() {
		$rows = $this->find('all', array(
			'conditions' => array('tipo' => 0),
			'fields' => array('asignatura_id', 'MAX(DATE(fecha)) AS fecha', 'usuario_id'),
			'group' => array('asignatura_id', 'usuario_id')
		));

		$out = array();
		foreach ($rows as $row) {
			$out[$row[$this->alias]['usuario_id']][$row[$this->alias]['asignatura_id']] = current($row[0]);
		}
		return $out;
	}
}
