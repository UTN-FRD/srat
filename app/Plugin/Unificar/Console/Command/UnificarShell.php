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
	public $uses = array('Asignatura', 'Cargo', 'Horario');

/**
 * Unifica las bases de datos de las distintas carreras
 *
 * @return void
 */
	public function main() {
		$this->_combinarTablas();
		$this->_unificarAsignaturas();
		$this->_unificarCargos();
		$this->_unificarHorarios();
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
 * Unifica los cargos
 *
 * @return void
 *
 * @throws Exception Cuando se produce un error al guardar los registros
 */
	protected function _unificarCargos() {
		try {
			$registros = $this->_obtenerRegistrosCargos();
			if (!$this->Cargo->saveMany($registros)) {
				throw new Exception(
					'No se unificaron cargos debido a un ' .
					($this->Cargo->validationErrors ? 'error en la validación de los datos.' : 'error interno.')
				);
			}
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}
	}

/**
 * Unifica los horarios
 *
 * @return void
 *
 * @throws Exception Cuando se produce un error al guardar los registros
 */
	protected function _unificarHorarios() {
		try {
			$registros = $this->_obtenerRegistrosHorarios();
			if (!$this->Horario->saveMany($registros)) {
				throw new Exception(
					'No se unificaron cargos debido a un ' .
					($this->Horario->validationErrors ? 'error en la validación de los datos.' : 'error interno.')
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
 * Obtiene los registros de los cargos de todas las carreras
 *
 * @return array
 */
	protected function _obtenerRegistrosCargos() {
		$mapa = $this->_generarMapaCargos();
		$registros = array();

		$hasOne = $this->Cargo->hasOne;
		$virtualFields = $this->Cargo->virtualFields;

		$this->Cargo->hasOne = array();
		$this->Cargo->virtualFields = array();

		foreach ($this->_obtenerConexiones() as $conexion => $base) {
			$this->Cargo->setDataSource($conexion);
			$this->Cargo->Asignatura->setDataSource($conexion);
			$this->Cargo->Dedicacion->setDataSource($conexion);
			$this->Cargo->Grado->setDataSource($conexion);
			$this->Cargo->Tipo->setDataSource($conexion);
			$this->Cargo->Usuario->setDataSource($conexion);

			$filas = $this->Cargo->find('all', array(
				'recursive' => 0,
				'fields' => array(
					'id', 'Asignatura.carrera_id', 'Asignatura.materia_id', 'dedicacion', 'resolucion',
					'Carrera.nombre', 'Dedicacion.nombre', 'Grado.nombre', 'Materia.nombre', 'Tipo.nombre',
					'Usuario.legajo'
				),
				'joins' => array(
					array(
						'table' => 'asignaturas_carreras',
						'alias' => 'Carrera',
						'type' => 'INNER',
						'conditions' => array(
							'Carrera.id = Asignatura.carrera_id'
						)
					),
					array(
						'table' => 'asignaturas_materias',
						'alias' => 'Materia',
						'type' => 'INNER',
						'conditions' => array(
							'Materia.id = Asignatura.materia_id'
						)
					)
				)
			));

			$this->Cargo->setDataSource('default');
			$this->Cargo->Asignatura->setDataSource('default');
			$this->Cargo->Dedicacion->setDataSource('default');
			$this->Cargo->Grado->setDataSource('default');
			$this->Cargo->Tipo->setDataSource('default');
			$this->Cargo->Usuario->setDataSource('default');

			foreach ($filas as $fila) {
				unset($fila['Cargo']['id'], $fila['Asignatura']);
				$clave = sha1(serialize($fila));
				if (!isset($registros[$clave])) {
					$asignaturaId = null;
					$usuarioId = null;
					$tipoId = null;
					$gradoId = null;
					$dedicacionId = null;

					$asignatura = sprintf(
						'%s: %s',
						$fila['Carrera']['nombre'],
						$fila['Materia']['nombre']
					);
					if (isset($mapa['Asignatura'][$asignatura])) {
						$asignaturaId = $mapa['Asignatura'][$asignatura];
					} else {
						$this->out("> La asignatura '" . $asignatura . "' no se encuentra definida.");
					}
					if (isset($mapa['Usuario'][$fila['Usuario']['legajo']])) {
						$usuarioId = $mapa['Usuario'][$fila['Usuario']['legajo']];
					} else {
						$this->out("> El legajo de usuario '" . $fila['Usuario']['legajo'] . "' no se encuentra definido.");
					}
					if (isset($mapa['Tipo'][$fila['Tipo']['nombre']])) {
						$tipoId = $mapa['Tipo'][$fila['Tipo']['nombre']];
					} else {
						$this->out("> El tipo de cargo '" . $fila['Tipo']['nombre'] . "' no se encuentra definido.");
					}
					if (isset($mapa['Grado'][$fila['Grado']['nombre']])) {
						$gradoId = $mapa['Grado'][$fila['Grado']['nombre']];
					} else {
						$this->out("> El grado '" . $fila['Grado']['nombre'] . "' no se encuentra definido.");
					}
					if (isset($mapa['Dedicacion'][$fila['Dedicacion']['nombre']])) {
						$dedicacionId = $mapa['Dedicacion'][$fila['Dedicacion']['nombre']];
					} else {
						$this->out("> La dedicacion '" . $fila['Dedicacion']['nombre'] . "' no se encuentra definida.");
					}
					$registros[$clave] = array(
						'asignatura_id' => $asignaturaId,
						'usuario_id' => $usuarioId,
						'tipo_id' => $tipoId,
						'grado_id' => $gradoId,
						'dedicacion_id' => $dedicacionId,
						'dedicacion' => (float)$fila['Cargo']['dedicacion'],
						'resolucion' => $fila['Cargo']['resolucion']
					);
				}
			}
		}

		$this->Cargo->hasOne = $hasOne;
		$this->Cargo->virtualFields = $this->Cargo->virtualFields;

		return array_values($registros);
	}

/**
 * Obtiene los registros de los horarios de todas las carreras
 *
 * @return array
 */
	protected function _obtenerRegistrosHorarios() {
		$mapa = $this->_generarMapaHorarios();
		$registros = array();

		foreach ($this->_obtenerConexiones() as $conexion => $base) {
			$this->Horario->setDataSource($conexion);
			$this->Horario->Asignatura->setDataSource($conexion);

			$filas = $this->Horario->find('all', array(
				'recursive' => 0,
				'fields' => array(
					'id', 'asignatura_id', 'dia', 'entrada', 'salida', 'Carrera.nombre', 'Materia.nombre'
				),
				'joins' => array(
					array(
						'table' => 'asignaturas_carreras',
						'alias' => 'Carrera',
						'type' => 'INNER',
						'conditions' => array(
							'Carrera.id = Asignatura.carrera_id'
						)
					),
					array(
						'table' => 'asignaturas_materias',
						'alias' => 'Materia',
						'type' => 'INNER',
						'conditions' => array(
							'Materia.id = Asignatura.materia_id'
						)
					)
				)
			));

			$this->Horario->setDataSource('default');
			$this->Horario->Asignatura->setDataSource('default');

			foreach ($filas as $fila) {
				unset($fila['Horario']['id'], $fila['Horario']['asignatura_id']);
				$clave = sha1(serialize($fila));
				if (!isset($registros[$clave])) {
					$asignaturaId = null;

					$asignatura = sprintf(
						'%s: %s',
						$fila['Carrera']['nombre'],
						$fila['Materia']['nombre']
					);
					if (isset($mapa['Asignatura'][$asignatura])) {
						$asignaturaId = $mapa['Asignatura'][$asignatura];
					} else {
						$this->out("> La asignatura '" . $asignatura . "' no se encuentra definida.");
					}

					$registros[$clave] = array(
						'asignatura_id' => $asignaturaId,
						'dia' => $fila['Horario']['dia'],
						'entrada' => $fila['Horario']['entrada'],
						'salida' => $fila['Horario']['salida']
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
 * Genera un mapa de datos de las tablas Asignaturas, Dedicaciones, Grados y Tipos de cargos
 *
 * @return array
 */
	protected function _generarMapaCargos() {
		$registros = array();

		foreach (array('Asignatura', 'Dedicacion', 'Grado', 'Tipo', 'Usuario') as $modelo) {
			$opciones = array();
			if ($modelo === 'Asignatura') {
				$opciones['recursive'] = 0;
			} elseif ($modelo === 'Usuario') {
				$opciones['fields'] = array('id', 'legajo');
			}

			$registros[$modelo] = array_flip($this->Cargo->{$modelo}->find('list', $opciones));
		}

		return $registros;
	}

/**
 * Genera un mapa de datos de la tabla Asignaturas
 *
 * @return array
 */
	protected function _generarMapaHorarios() {
		return array(
			'Asignatura' => array_flip($this->Horario->Asignatura->find('list', array('recursive' => 0)))
		);
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
