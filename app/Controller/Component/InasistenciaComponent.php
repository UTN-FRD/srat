<?php
/**
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

/**
 * Dependencias
 */
App::uses('Component', 'Controller');

/**
 * Actualización de inasistencia
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class InasistenciaComponent extends Component {

/**
 * Clave de cache
 *
 * @var string
 */
	private $__cacheKey = 'absences';

/**
 * Versión del cache
 *
 * @var string
 */
	private $__cacheVersion;

/**
 * Registro
 *
 * @var Registro
 */
	public $Registro;

/**
 * Inicialización
 *
 * @param Controller $controller Instancia del controlador
 *
 * @return void
 */
	public function initialize(Controller $controller) {
		$this->__cacheVersion = date('Y-m-d');
		$this->Registro = ClassRegistry::init('Registro');

		$result = (array)Cache::read($this->__cacheKey);
		if (!isset($result[$this->__cacheVersion])) {
			$this->__updateAbsences();
			Cache::write($this->__cacheKey, array($this->__cacheVersion => true));
		}
	}

/**
 * Genera una lista de fechas para un período dado entre dos fechas según los días especificados
 *
 * @param string $start Fecha de inicio
 * @param string $end Fecha de fin
 * @param array $days Número de cada día
 *
 * @return array Fechas
 */
	private function __generateDateList($start, $end, $days = array(0, 1, 2, 3, 4, 5, 6)) {
		try {
			$period = new DatePeriod(
				new DateTime($start),
				DateInterval::createFromDateString('1 day'),
				new DateTime($end),
				DatePeriod::EXCLUDE_START_DATE
			);
		} catch (Exception $e) {
			return array();
		}

		$out = array();
		$days = array_flip($days);
		foreach ($period as $date) {
			if (isset($days[$date->format('w')])) {
				$out[] = $date->format('Y-m-d');
			}
		}
		return $out;
	}

/**
 * Obtiene la última fecha de inasistencia para todos los cargos registrados
 *
 * @return array Inasistencias
 */
	private function __getAbsencesList() {
		$out = array();
		$rows = $this->Registro->find('all', array(
			'conditions' => array('tipo' => 0),
			'fields' => array('asignatura_id', 'usuario_id', 'MAX(fecha) AS fecha'),
			'group' => array('asignatura_id', 'usuario_id')
		));

		foreach ($rows as $row) {
			$key = $row['Registro']['asignatura_id'] . $row['Registro']['usuario_id'];
			$out[$key] = array_merge($row['Registro'], $row[0]);
		}
		return $out;
	}

/**
 * Obtiene todos los días definidos para cada cargo
 *
 * @return array Días
 */
	private function __getDaysList() {
		$this->Registro->Usuario->Cargo->unbindModel(array(
			'belongsTo' => array('Asignatura', 'Dedicacion', 'Grado', 'Tipo', 'Usuario')
		));
		$this->Registro->Usuario->Cargo->bindModel(array(
			'hasOne' => array(
				'Horario' => array(
					'conditions' => 'Cargo.asignatura_id = Horario.asignatura_id',
					'foreignKey' => false
				)
			)
		));

		$rows = $this->Registro->Usuario->Cargo->find('all', array(
			'conditions' => array('NOT' => array('Horario.dia' => null)),
			'fields' => array('Cargo.asignatura_id', 'Cargo.usuario_id', 'Horario.dia'),
			'recursive' => 0
		));

		$out = array();
		foreach ($rows as $row) {
			$key = $row['Cargo']['asignatura_id'] . $row['Cargo']['usuario_id'];
			if (!isset($out[$key])) {
				$out[$key] = array(
					'asignatura_id' => $row['Cargo']['asignatura_id'],
					'usuario_id' => $row['Cargo']['usuario_id'],
					'days' => array()
				);
			}
			$out[$key]['days'][] = $row['Horario']['dia'];
		}
		return $out;
	}

/**
 * Actualiza la inasistencia
 *
 * @return void
 */
	private function __updateAbsences() {
		$oldest = $this->Registro->find('first', array('fields' => array('MIN(fecha) as min_fecha')));
		if (!empty($oldest)) {
			$absences = $this->__getAbsencesList();
			$oldest = date('Y-m-d', strtotime($oldest[0]['min_fecha'] . ' -1 day'));
			$today = date('Y-m-d');

			foreach ($this->__getDaysList() as $cargo => $data) {
				$start = $oldest;
				if (isset($absences[$cargo])) {
					$start = $absences[$cargo]['fecha'];
				}

				$dates = $this->__generateDateList($start, $today, $data['days']);
				if (!empty($dates)) {
					$rows = $this->Registro->find('list', array(
						'fields' => array('id', 'fecha'),
						'conditions' => array(
							'asignatura_id' => $data['asignatura_id'],
							'fecha' => $dates,
							'tipo' => 1,
							'usuario_id' => $data['usuario_id']
						)
					));

					$rows = array_map(function ($date) use ($data) {
						return sprintf('(0, %d, %d, \'%s\')', $data['asignatura_id'], $data['usuario_id'], $date);
					}, array_diff($dates, $rows));

					$this->Registro->query(sprintf(
						'INSERT INTO `%s` (`tipo`, `asignatura_id`, `usuario_id`, `fecha`) VALUES %s',
						$this->Registro->useTable,
						implode(',', $rows)
					));
				}
			}
		}
	}
}
