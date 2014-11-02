<?php
/**
 * Sistema de Registro de Asistencia y Temas
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
App::uses('AppShell', 'Console');

/**
 * Usuarios
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class UsuariosShell extends AppShell {

/**
 * Modelos
 *
 * @var array
 */
	public $uses = array('Usuario');

/**
 * Restablece la contraseña de un usuario
 *
 * @return void
 */
	public function restablecer() {
		if (empty($this->args)) {
			$this->error('No se ha especificado el número de legajo del usuario.');
		}

		$legajo = current($this->args);
		if (!filter_var($legajo, FILTER_VALIDATE_INT)) {
			$this->error('El número de legajo no es válido.');
		}

		$row = $this->Usuario->findByLegajo($legajo);
		if (!$row) {
			$this->error(sprintf('El número de legajo \'%d\' no existe.', $legajo));
		}

		$option = strtoupper($this->in(
			sprintf('¿Está seguro que desea restablecer la contraseña del usuario (%d) %s?', $legajo, $row['Usuario']['nombre_completo']),
			array('Y', 'N'),
			'N'
		));
		if ($option === 'Y') {
			$result = $this->Usuario->save(
				array('id' => $row['Usuario']['id'], 'reset' => 1),
				array('fieldList' => array('id', 'password', 'reset'))
			);

			if ($result) {
				$this->out('La operación solicitada se ha completado exitosamente.');
			} else {
				$this->error('No fue posible restablecer la contraseña debido a un error interno.');
			}
		}
	}
}
