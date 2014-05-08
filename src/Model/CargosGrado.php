<?php
/**
 * Sistema de Registro de Asistenca y Temas
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
App::uses('AppModel', 'Model');

/**
 * CargosGrado
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class CargosGrado extends AppModel {

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Cargo' => array(
			'foreignKey' => 'grado_id'
		)
	);
}
