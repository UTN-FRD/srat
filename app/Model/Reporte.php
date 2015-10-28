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

/**
 * Obtiene todas las asignaturas que se encuentran en la tabla de registros
 *
 * @return array Asignaturas
 */
	public function getAsignaturas() {
		$result = $this->Asignatura->Registro->find('list', array(
			'fields' => array('Registro.id', 'Registro.asignatura_id'),
			'group' => array('Registro.asignatura_id'),
			'recursive' => 0
		));

		$id = array();
		if (!empty($result) && is_array($result)) {
			$id = array_values($result);
		}

		$this->Asignatura->unbindModel(array(
			'belongsTo' => array('Area', 'Nivel', 'Tipo')
		));
		return $this->Asignatura->find('list', array(
			'conditions' => array('Asignatura.id' => $id),
			'order' => array('Materia.nombre' => 'asc'),
			'recursive' => 0
		));
	}

/**
 * Obtiene todos los usuarios que se encuentran en la tabla de registros
 *
 * @param int|null $aid Identificador de la asignatura
 *
 * @return array Usuarios
 */
	public function getUsuarios($aid = null) {
		$conditions = array();
		if ($aid) {
			$conditions = array('Registro.asignatura_id' => $aid);
		}

		$result = $this->Usuario->Registro->find('list', array(
			'conditions' => $conditions,
			'fields' => array('Registro.id', 'Registro.usuario_id'),
			'group' => array('Registro.usuario_id'),
			'recursive' => 0
		));

		$id = array();
		if (!empty($result) && is_array($result)) {
			$id = array_values($result);
		}

		$virtualFields = $this->Usuario->virtualFields;
		$this->Usuario->virtualFields = array(
			'nombre_completo' => 'CONCAT("(", Usuario.legajo, ")", " ", Usuario.nombre, " ", Usuario.apellido)'
		);

		$result = $this->Usuario->find('list', array(
			'conditions' => array('Usuario.id' => $id),
			'order' => array('Usuario.legajo' => 'asc')
		));

		$this->Usuario->virtualFields = $virtualFields;
		return $result;
	}
}
