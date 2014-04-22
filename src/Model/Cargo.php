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
 * Cargo
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class Cargo extends AppModel {

/**
 * belongsTo
 *
 * @var array
 */
	public $belongsTo = array(
		'Asignatura',
		'Usuario',
		'Dedicacion' => array(
			'className' => 'CargosDedicacion',
			'foreignKey' => 'dedicacion_id'
		),
		'Grado' => array(
			'className' => 'CargosGrado',
			'foreignKey' => 'grado_id'
		),
		'Tipo' => array(
			'className' => 'CargosTipo',
			'foreignKey' => 'tipo_id'
		)
	);

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Asistencia'
	);
}
