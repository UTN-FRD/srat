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
 * Asistencia
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class Asistencia extends AppModel {

/**
 * Comportamientos
 *
 * @var array
 */
	public $actsAs = array(
		'Search.Searchable'
	);

/**
 * belongsTo
 *
 * @var array
 */
	public $belongsTo = array(
		'Cargo'
	);

/**
 * Campos de búsqueda
 *
 * @var array
 */
	public $filterArgs = array(
		'buscar' => array(
			'field' => array(
				'Asistencia.obs', 'Carrera.nombre', 'Materia.nombre', 'Usuario.apellido', 'Usuario.nombre',
				'CONCAT(Usuario.nombre, " ", Usuario.apellido)'
			),
			'type' => 'like'
		)
	);
}
