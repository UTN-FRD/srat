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
App::uses('Model', 'Model');

/**
 * Modelo de datos de la aplicación
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class AppModel extends Model {

/**
 * Nombre del campo utilizado
 * por el tipo de búsqueda `list`
 *
 * @var string
 */
	public $displayField = 'nombre';
}
