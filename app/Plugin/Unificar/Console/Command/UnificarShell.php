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

/**
 * Unificación de bases de datos
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class UnificarShell extends Shell {

/**
 * Unifica las bases de datos de las distintas carreras
 *
 * @return void
 */
	public function main() {
		$this->_combinarTablas();
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
