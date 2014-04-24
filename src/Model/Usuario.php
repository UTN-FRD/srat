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
 * Usuario
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class Usuario extends AppModel {

/**
 * belongsTo
 *
 * @var array
 */
	public $belongsTo = array(
		'Rol' => array(
			'className' => 'UsuariosRol',
			'foreignKey' => 'rol_id'
		)
	);

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Cargo'
	);

/**
 * Campos virtuales
 *
 * @var array
 */
	public $virtualFields = array(
		'nombre_completo' => 'CONCAT(Usuario.nombre, " ", Usuario.apellido)'
	);
}
