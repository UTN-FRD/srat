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
 * AsignaturasMateria
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class AsignaturasMateria extends AppModel {

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Asignatura' => array(
			'foreignKey' => 'materia_id'
		)
	);
}
