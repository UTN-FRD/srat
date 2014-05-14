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
 * UsuariosRolFixture
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class UsuariosRolFixture extends CakeTestFixture {

/**
 * Importación
 *
 * @var array
 */
	public $import = array('model' => 'UsuariosRol');

/**
 * Registros
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'nombre' => 'Administrador',
			'obs' => null
		),
		array(
			'id' => '2',
			'nombre' => 'Docente',
			'obs' => null
		)
	);
}
