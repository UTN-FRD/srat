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
 * Asignatura
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class Asignatura extends AppModel {

/**
 * belongsTo
 *
 * @var array
 */
	public $belongsTo = array(
		'Area' => array(
			'className' => 'AsignaturasArea',
			'foreignKey' => 'area_id'
		),
		'Carrera' => array(
			'className' => 'AsignaturasCarrera',
			'foreignKey' => 'carrera_id'
		),
		'Materia' => array(
			'className' => 'AsignaturasMateria',
			'foreignKey' => 'materia_id'
		),
		'Nivel' => array(
			'className' => 'AsignaturasNivel',
			'foreignKey' => 'nivel_id'
		),
		'Tipo' => array(
			'className' => 'AsignaturasTipo',
			'foreignKey' => 'tipo_id'
		)
	);

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Cargo',
		'Horario'
	);
}
