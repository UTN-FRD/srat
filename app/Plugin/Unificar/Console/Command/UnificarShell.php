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
App::uses('ConnectionManager', 'Model');
App::uses('Hash', 'Utility');

/**
 * Unificación de bases de datos
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class UnificarShell extends Shell {

/**
 * Modelos
 *
 * @var array
 */
	public $uses = array('Asignatura');

/**
 * Unifica las bases de datos de las distintas carreras
 *
 * @return void
 */
	public function main() {
		$this->_combinarTablas();
		$this->_unificarAsignaturas();
	}

/**
 * Ejecuta el procedimiento almacenado CombinarTablas()
 *
 * @return void
 */
	protected function _combinarTablas() {
		try {
			$conexion = ConnectionManager::getDataSource('default');
			$conexion->query(sprintf('CALL %s.CombinarTablas()', $conexion->getSchemaName()));
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}
	}

/**
 * Unifica las asignaturas
 *
 * @return void
 *
 * @throws Exception Cuando se produce un error al guardar los registros
 */
	protected function _unificarAsignaturas() {
		try {
			$registros = $this->_obtenerRegistrosAsignaturas();
			if (!$this->Asignatura->saveMany($registros)) {
				throw new Exception(
					'No se unificaron asignaturas debido a un ' .
					($this->Asignatura->validationErrors ? 'error en la validación de los datos.' : 'error interno.')
				);
			}
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}
	}

/**
 * Obtiene los registros de las asignaturas de todas las carreras
 *
 * @return array
 */
	protected function _obtenerRegistrosAsignaturas() {
		$mapa = $this->_generarMapaAsignaturas();
		$registros = array();

		foreach ($this->_obtenerConexiones() as $conexion => $base) {
			$this->Asignatura->setDataSource($conexion);
			$this->Asignatura->Area->setDataSource($conexion);
			$this->Asignatura->Carrera->setDataSource($conexion);
			$this->Asignatura->Materia->setDataSource($conexion);
			$this->Asignatura->Nivel->setDataSource($conexion);
			$this->Asignatura->Tipo->setDataSource($conexion);

			$filas = $this->Asignatura->find('all', array(
				'fields' => array('Carrera.nombre', 'Materia.nombre', 'Area.nombre', 'Nivel.nombre', 'Tipo.nombre'),
				'recursive' => 0
			));

			$this->Asignatura->setDataSource('default');
			$this->Asignatura->Area->setDataSource('default');
			$this->Asignatura->Carrera->setDataSource('default');
			$this->Asignatura->Materia->setDataSource('default');
			$this->Asignatura->Nivel->setDataSource('default');
			$this->Asignatura->Tipo->setDataSource('default');

			foreach (Hash::remove($filas, '{n}.Asignatura') as $fila) {
				$clave = sha1(serialize($fila));
				$areaId = null;
				$carreraId = null;
				$materiaId = null;
				$nivelId = null;
				$tipoId = null;
				if (!isset($registros[$clave])) {
					if (isset($mapa['Area'][$fila['Area']['nombre']])) {
						$areaId = $mapa['Area'][$fila['Area']['nombre']];
					} else {
						$this->out("> El área '" . $fila['Area']['nombre'] . "' no se encuentra definida.");
					}
					if (isset($mapa['Carrera'][$fila['Carrera']['nombre']])) {
						$carreraId = $mapa['Carrera'][$fila['Carrera']['nombre']];
					} else {
						$this->out("> La Carrera '" . $fila['Carrera']['nombre'] . "' no se encuentra definida.");
					}
					if (isset($mapa['Materia'][$fila['Materia']['nombre']])) {
						$materiaId = $mapa['Materia'][$fila['Materia']['nombre']];
					} else {
						$this->out("> La materia '" . $fila['Materia']['nombre'] . "' no se encuentra definida.");
					}
					if (isset($mapa['Nivel'][$fila['Nivel']['nombre']])) {
						$nivelId = $mapa['Nivel'][$fila['Nivel']['nombre']];
					} else {
						$this->out("> El nivel '" . $fila['Nivel']['nombre'] . "' no se encuentra definido.");
					}
					if (isset($mapa['Tipo'][$fila['Tipo']['nombre']])) {
						$tipoId = $mapa['Tipo'][$fila['Tipo']['nombre']];
					} else {
						$this->out("> El tipo de nivel '" . $fila['Tipo']['nombre'] . "' no se encuentra definido.");
					}
					$registros[$clave] = array(
						'area_id' => $areaId,
						'carrera_id' => $carreraId,
						'materia_id' => $materiaId,
						'nivel_id' => $nivelId,
						'tipo_id' => $tipoId
					);
				}
			}
		}

		return array_values($registros);
	}

/**
 * Genera un mapa de datos de las tablas Áreas, Carreras, Materias, Niveles y Tipos de niveles
 *
 * @return array
 */
	protected function _generarMapaAsignaturas() {
		$registros = array();

		foreach (array('Area', 'Carrera', 'Materia', 'Tipo', 'Nivel') as $modelo) {
			$registros[$modelo] = array_flip($this->Asignatura->{$modelo}->find('list'));
		}

		return $registros;
	}

/**
 * Obtiene un listado de las conexiones a las bases de datos y la base de datos correspondiente
 *
 * @return array
 */
	protected function _obtenerConexiones() {
		static $conexiones = null;
		if ($conexiones === null) {
			foreach (ConnectionManager::enumConnectionObjects() as $nombre => $opciones) {
				if ($nombre !== 'default' && $nombre !== 'test') {
					$conexiones[$nombre] = $opciones['database'];
				}
			}
		}

		return $conexiones;
	}

/**
 * getOptionParser
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(
			'Unifica las bases de datos de las distintas carreras.'
		);
		return $parser;
	}
}
