<?php
/**
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo LICENCIA.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

/**
 * Dependencias
 */
App::uses('Model', 'Model');

/**
 * Modelo de datos de la aplicación
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
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
 * @var int
 */
	public $recursive = -1;

/**
 * Valida que un registro existe en un modelo asociado
 *
 * @param array $check Nombre del campo y su valor
 * @param string $model Modelo asociado
 *
 * @return bool `true` en caso exitoso o `false` en caso contrario
 */
	public function validateExists($check, $model) {
		if (!empty($check) && !empty($model)) {
			if (isset($this->{$model})) {
				return $this->{$model}->exists(current($check));
			}
		}
		return false;
	}

/**
 * Valida que todos los valores de los campos pasados sean únicos
 *
 * @param array $check Nombre del campo y su valor
 * @param array $fields Campos adicionales
 *
 * @return bool `true` en caso exitoso o `false` en caso contrario
 */
	public function validateUnique($check, $fields = array()) {
		if (!empty($check)) {
			if ($fields) {
				$fields = array_flip((array)$fields);
				$check = array_merge((array)$check, $fields);
			}
			return $this->isUnique(array_keys($check), false);
		}
		return false;
	}

/**
 * Comprueba si un registro se encuentra asociado examinando las asociaciones del modelo
 *
 * @param int|string|array $id Identificador del registro
 *
 * @return bool `true` en caso exitoso o `false` en caso contrario
 */
	public function hasAssociations($id = null) {
		if ($id) {
			if (is_array($id)) {
				$id = $id[0];
			}
			$this->id = $id;
		}
		$id = $this->id;

		if ($id) {
			foreach ($this->getAssociated() as $model => $type) {
				if (in_array($type, array('hasOne', 'hasMany'))) {
					$foreignKey = $this->{$type}[$model]['foreignKey'];
					if ($foreignKey) {
						if ($this->{$model}->hasAny(array($foreignKey => $id))) {
							return true;
						}
					}
				}
			}
		}
		return false;
	}
}
