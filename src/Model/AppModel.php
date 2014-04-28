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

/**
 * Nivel de recursividad
 *
 * @var integer
 */
	public $recursive = -1;

/**
 * Valida que un registro existe en un modelo asociado
 *
 * @param array $check Nombre del campo y su valor
 * @param string $model Modelo asociado
 *
 * @return boolean `true` en caso exitoso o `false` en caso contrario
 */
	public function validateExists($check, $model) {
		if (!empty($check) && isset($this->{$model})) {
			return $this->{$model}->exists(current($check));
		}
		return false;
	}
}
