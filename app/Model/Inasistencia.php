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
 * Inasistencia
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class Inasistencia extends AppModel {

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
		'Asignatura' => array(
			'fields' => array('id')
		),
		'Usuario' => array(
			'fields' => array('apellido', 'legajo', 'nombre')
		)
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
			'fields' => array('nombre'),
			'foreignKey' => false
		),
		'Materia' => array(
			'className' => 'AsignaturasMateria',
			'conditions' => 'Materia.id = Asignatura.materia_id',
			'fields' => array('nombre'),
			'foreignKey' => false
		)
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
		)
	);

/**
 * Nivel de recursividad
 *
 * @var int
 */
	public $recursive = 0;

/**
 * Tabla
 *
 * @var string
 */
	public $useTable = 'registros';

/**
 * Campos virtuales
 *
 * @var array
 */
	public $virtualFields = array(
		'asignatura' => 'CONCAT(Carrera.nombre, ": ", Materia.nombre)',
		'docente' => 'CONCAT(Usuario.apellido, ", ", Usuario.nombre)'
	);

/**
 * beforeFind
 *
 * @param array $query Datos utilizados para ejecutar la consulta
 *
 * @return bool `true` para continuar la operación de búsqueda, `false` para cancelarla o
 */
	public function beforeFind($query) {
		$query['conditions'][$this->alias . '.tipo'] = 0;
		return $query;
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
				'Usuario.legajo LIKE' => '%' . $value . '%',
				'Usuario.apellido LIKE' => '%' . $value . '%',
				'Usuario.nombre LIKE' => '%' . $value . '%'
			)
		);

		if (preg_match('/^[0-9\/]+$/', $value)) {
			$date = date_create_from_format('d/m/Y', $value);
			if ($date) {
				$condition['OR']['Inasistencia.fecha LIKE'] = '%' . $date->format('Y-m-d') . '%';
			} else {
				$condition['OR']['Inasistencia.fecha LIKE'] = '%' . $value . '%';
			}
		}

		return $condition;
	}

/**
 * Devuelve el total de inasistencias del día de ayer
 *
 * @return int Total de inasistencias
 */
	public function getYesterdaysTotal() {
		$cacheKey = 'absences_total';
		$result = Cache::read($cacheKey);
		if ($result === false) {
			$result = $this->find('count', array(
				'conditions' => array('CAST(fecha as DATE)' => date('Y-m-d', strtotime('-1 day'))),
				'recursive' => -1
			));
			Cache::write($cacheKey, $result);
		}
		return $result;
	}
}
