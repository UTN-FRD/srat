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
 * Unifica las bases de datos de las distintas carreras en una sola
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class UnificarShell extends Shell {

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
