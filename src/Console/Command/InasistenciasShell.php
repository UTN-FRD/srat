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
App::uses('AppShell', 'Console');

/**
 * Actualización de inasistencia
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class InasistenciasShell extends AppShell {

/**
 * Modelos
 *
 * @var array
 */
	public $uses = array('Registro');

/**
 * Actualiza la inasistencia
 *
 * @return void
 */
	public function main() {
		$result = $this->Registro->find('first', array(
			'conditions' => array('tipo' => 1),
			'fields' => array('fecha'),
			'order' => array('id' => 'asc')
		));
		if (!empty($result)) {
			$absences = $this->__getAbsencesList();
			$today = date('Y-m-d');

			foreach ($this->__getDaysList() as $cargo => $days) {
				$start = $result['Registro']['fecha'];
				if (isset($absences[$cargo])) {
					$start = $absences[$cargo];
				}

				$dates = $this->__generateDateList($start, $today, $days);
				if (!empty($dates)) {
					$rows = $this->Registro->find('list', array(
						'conditions' => array('cargo_id' => $cargo, 'tipo' => 1, 'fecha' => $dates),
						'fields' => array('id', 'fecha')
					));

					$rows = array_map(function($date) use ($cargo) {
						return sprintf('(%d, 0, \'%s\')', $cargo, $date);
					}, array_diff($dates, $rows));

					$this->Registro->query(sprintf(
						'INSERT INTO `%s` (`cargo_id`,`tipo`,`fecha`) VALUES %s',
						$this->Registro->useTable,
						implode(',', $rows)
					));
				}
			}
			$this->out('La operación se ha completado exitosamente.');
		} else {
			$this->out('No se han registrado asistencias.');
		}
	}

/**
 * Obtiene la última fecha de inasistencia para cada cargo
 *
 * @return array Inasistencias
 */
	private function __getAbsencesList() {
		$this->Registro->displayField = 'max_fecha';
		$this->Registro->virtualFields = array('max_fecha' => 'MAX(fecha)');

		return $this->Registro->find('list', array(
			'conditions' => array('tipo' => 0),
			'fields' => array('cargo_id', 'max_fecha'),
			'group' => array('cargo_id')
		));
	}

/**
 * Obtiene los días para cada cargo
 *
 * @return array Días
 */
	private function __getDaysList() {
		$this->Registro->Cargo->unbindModel(array('belongsTo' => array('Dedicacion', 'Grado', 'Tipo', 'Usuario')));
		$this->Registro->Cargo->bindModel(array(
			'hasOne' => array(
				'Horario' => array(
					'conditions' => 'Asignatura.id = Horario.asignatura_id',
					'foreignKey' => false
				)
			)
		));

		$rows = $this->Registro->Cargo->find('all', array(
			'conditions' => array('NOT' => array('Horario.dia' => null)),
			'fields' => array('Cargo.id', 'Horario.dia'),
			'recursive' => 0
		));

		$out = array();
		foreach ($rows as $row) {
			$out[$row['Cargo']['id']][] = $row['Horario']['dia'];
		}
		return $out;
	}

/**
 * Genera una lista de fechas en un período dado para los días especificados
 *
 * @param string $start Fecha de inicio
 * @param string $end Fecha de fin
 * @param array $days Número de cada día
 *
 * @return array Fechas
 */
	private function __generateDateList($start, $end, $days = array(0, 1, 2, 3, 4, 5, 6)) {
		$out = array();

		try {
			$period = new DatePeriod(
				new DateTime($start),
				DateInterval::createFromDateString('1 day'),
				new DateTime($end),
				DatePeriod::EXCLUDE_START_DATE
			);
		} catch (Exception $e) {
			return $out;
		}

		foreach ($period as $date) {
			if (in_array($date->format('w'), $days)) {
				$out[] = $date->format('Y-m-d');
			}
		}

		return $out;
	}
}
