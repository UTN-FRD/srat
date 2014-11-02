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
App::uses('AppModel', 'Model');

/**
 * CargosDedicacion
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class CargosDedicacion extends AppModel {

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Cargo' => array(
			'foreignKey' => 'dedicacion_id'
		)
	);
}
