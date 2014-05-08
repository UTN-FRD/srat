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
 * Reporte
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class Reporte extends AppModel {

/**
 * Tabla
 *
 * @var boolean|string
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
		'Cargo' => array(
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
			'notEmpty' => array(
				'rule' => 'notEmpty',
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
		'usuario_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
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
			'notEmpty' => array(
				'rule' => 'notEmpty',
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
			'notEmpty' => array(
				'rule' => 'notEmpty',
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
				'rule' => 'validEndDate',
				'message' => 'La fecha seleccionada debe ser igual o mayor que la indicada en el campo previo'
			)
		)
	);

/**
 * Valida que la fecha del campo `hasta` sea igual o mayor que la del campo `desde`
 * sólo si se han especificado ambas fechas
 *
 * @param array $check Nombre del campo y su valor
 *
 * @return boolean `true` en caso exitoso o `false` en caso contrario
 */
	public function validEndDate($check) {
		if (!empty($this->data[$this->alias]['desde']) && !empty($this->data[$this->alias]['hasta'])) {
			$fromDate = (int)strtotime($this->data[$this->alias]['desde']);
			$toDate = (int)strtotime($this->data[$this->alias]['hasta']);

			return ($fromDate <= $toDate);
		}
		return true;
	}

/**
 * Obtiene todas las asignaturas asociadas a cargos que se encuetran en la tabla de asistencias
 *
 * @return array Asignaturas
 */
	public function getAsignaturas() {
		$result = $this->Cargo->Asistencia->find('list', array(
			'fields' => array('Asistencia.id', 'Cargo.asignatura_id'),
			'group' => array('Cargo.asignatura_id'),
			'recursive' => 0
		));

		$id = array();
		if (!empty($result) && is_array($result)) {
			$id = array_values($result);
		}

		$this->Cargo->Asignatura->unbindModel(array(
			'belongsTo' => array('Area', 'Nivel', 'Tipo')
		));
		return $this->Cargo->Asignatura->find('list', array(
			'conditions' => array('Asignatura.id' => $id),
			'order' => array('Materia.nombre' => 'asc'),
			'recursive' => 0
		));
	}

/**
 * Obtiene todos los usuarios asociados a cargos que se encuentran en la tabla de asistencias
 *
 * @param integer|null $aid Identificador de la asignatura
 *
 * @return array Usuarios
 */
	public function getUsuarios($aid = null) {
		$conditions = array();
		if ($aid) {
			$conditions = array('Cargo.asignatura_id' => $aid);
		}

		$result = $this->Cargo->Asistencia->find('list', array(
			'conditions' => $conditions,
			'fields' => array('Asistencia.id', 'Cargo.usuario_id'),
			'group' => array('Cargo.usuario_id'),
			'recursive' => 0
		));

		$id = array();
		if (!empty($result) && is_array($result)) {
			$id = array_values($result);
		}

		$virtualFields = $this->Cargo->Usuario->virtualFields;
		$this->Cargo->Usuario->virtualFields = array(
			'nombre_completo' => 'CONCAT("(", Usuario.legajo, ")", " ", Usuario.nombre, " ", Usuario.apellido)'
		);

		$result = $this->Cargo->Usuario->find('list', array(
			'conditions' => array('Usuario.id' => $id),
			'order' => array('Usuario.legajo' => 'asc')
		));

		$this->Cargo->Usuario->virtualFields = $virtualFields;
		return $result;
	}
}
