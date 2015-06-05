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
App::uses('AppShell', 'Console/Command');

/**
 * Registros
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class RegistrosShell extends AppShell {

/**
 * Modelos
 *
 * @var array
 */
	public $uses = array('Registro');

/**
 * Elimina el registro de asistencia
 *
 * @return void
 */
	public function eliminar() {
		$option = strtoupper($this->in(
			'¿Está seguro que desea eliminar el registro de asistencia?',
			array('S', 'N'),
			'N'
		));
		if ($option === 'S') {
			if ($this->Registro->getDataSource()->truncate($this->Registro->useTable)) {
				$this->out('La operación solicitada se ha completado exitosamente.');
			} else {
				$this->error('No fue posible eliminar el registro de asistencia debido a un error interno.');
			}
		}
	}
}
