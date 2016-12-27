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
App::uses('Component', 'Controller');

/**
 * Actualizador de inasistencia
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class AbsenceUpdaterComponent extends Component {

/**
 * Inicialización
 *
 * @param Controller $controller Instancia del controlador
 *
 * @return void
 */
	public function initialize(Controller $controller) {
		$cacheKey = 'absences';
		$cacheVersion = date('Y-m-d');
		$result = Cache::read($cacheKey);
		if ($result !== $cacheVersion) {
			$this->_updateAbsences();
			Cache::write($cacheKey, $cacheVersion);
		}
	}

/**
 * Actualiza la inasistencia
 *
 * @return void
 */
	protected function _updateAbsences() {
		$model = ClassRegistry::init('Registro');
		$oldestDate = $model->getFirstPresenceDate();
		if ($oldestDate) {
			$model->query(sprintf('UPDATE `%s` SET `tipo` = 0 WHERE `tipo` = 2', $model->useTable));
			$model->query(sprintf('UPDATE `%s` SET `computable` = 1 WHERE `tipo` = 1', $model->useTable));

			$absences = $model->getLastAbsenseDateByCargo();
			$oldestDate = date('Y-m-d', strtotime($oldestDate . ' -1 day'));
			$today = date('Y-m-d');
			$daysList = $model->Asignatura->Horario->getDaysListByCargo();
			foreach ($daysList as $user => $cargos) {
				foreach ($cargos as $cargo => $data) {
					if (isset($absences[$user][$cargo])) {
						$start = $absences[$user][$cargo];
					} elseif (!empty($data['created'])) {
						$start = $data['created'];
					} else {
						$start = $oldestDate;
					}

					$dates = $this->_generateDatesRange($start, $today, $data['days']);
					if (!empty($dates)) {
						$rows = $model->find('list', array(
							'fields' => array('id', 'solo_fecha'),
							'conditions' => array(
								'asignatura_id' => $cargo,
								'DATE(fecha)' => $dates,
								'tipo' => 1,
								'usuario_id' => $user
								)
							)
						);

						$rows = array_map(function ($date) use ($user, $cargo) {
							return sprintf(
								'(0, %d, %d, \'%s\')',
								$cargo,
								$user,
								date('Y-m-d H:i:s', strtotime($date))
								);
						}, array_diff($dates, $rows));

						if (!empty($rows)) {
							$model->query(sprintf(
								'INSERT INTO `%s` (`tipo`, `asignatura_id`, `usuario_id`, `fecha`) VALUES %s',
								$model->useTable,
								implode(',', $rows)
								)
							);
						}
					}
				}
			}

			$excludedDates = ClassRegistry::init('Periodo')->getDatesRange();
			if (!empty($excludedDates)) {
				$excludedDates = implode(',', $model->getDataSource()->value($excludedDates));
				$model->query(
					sprintf(
						'UPDATE `%s` SET tipo = 2 WHERE CAST(fecha as DATE) IN (%s) AND `tipo` = 0',
						$model->useTable,
						$excludedDates
					)
				);
				$model->query(
					sprintf(
						'UPDATE `%s` SET computable = 0 WHERE CAST(fecha as DATE) IN (%s) AND `tipo` = 1',
						$model->useTable,
						$excludedDates
					)
				);
			}
		}
	}

/**
 * Genera un rango de fechas según los días especificados excluyendo las fechas de comienzo y fin
 *
 * @param string $start Fecha de comienzo (YYYY-MM-DD)
 * @param string $end Fecha de fin (YYYY-MM-DD)
 * @param array $daysAllowed Número de días permitidos (0: Domingo, ..., 6: Sábado)
 *
 * @return array Rango de fechas
 */
	protected function _generateDatesRange($start, $end, $daysAllowed = array(0, 1, 2, 3, 4, 5, 6)) {
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

		if (!empty($daysAllowed)) {
			$daysAllowed = array_flip($daysAllowed);
			foreach ($period as $date) {
				if (isset($daysAllowed[$date->format('w')])) {
					$out[] = $date->format('Y-m-d');
				}
			}
		}
		return $out;
	}
}
