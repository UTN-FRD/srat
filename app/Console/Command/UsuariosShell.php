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
 * Usuarios
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class UsuariosShell extends AppShell {

/**
 * Modelos
 *
 * @var array
 */
	public $uses = array('Usuario');

/**
 * Habilita un usuario
 *
 * Uso:
 * cake usuarios habilitar [-l]
 *
 * Opciones:
 *
 * --legajo, -l   Número de legajo
 *
 * @return void
 */
	public function habilitar() {
		$user = $this->_findUser($this->param('legajo'));
		if (!$user) {
			$this->error('El número de legajo especificado no es válido.');
		}
		$user = current($user);

		if ($user['estado']) {
			$this->error('El usuario ya se encuentra habilitado.');
		}

		$option = strtoupper($this->in(
			sprintf('¿Está seguro que desea habilitar el usuario (%d) %s?', $user['legajo'], $user['nombre_completo']),
			array('S', 'N'),
			'N'
		));
		if ($option === 'S') {
			$this->Usuario->id = $user['id'];
			$result = $this->Usuario->saveField('estado', true, false);
			if ($result) {
				$this->out('La operación solicitada se ha completado exitosamente.');
			} else {
				$this->error('No fue posible cambiar el estado del usuario debido a un error interno.');
			}
		}
	}

/**
 * Deshabilita un usuario
 *
 * Uso:
 * cake usuarios deshabilitar [-l]
 *
 * Opciones:
 *
 * --legajo, -l   Número de legajo
 *
 * @return void
 */
	public function deshabilitar() {
		$user = $this->_findUser($this->param('legajo'));
		if (!$user) {
			$this->error('El número de legajo especificado no es válido.');
		}
		$user = current($user);

		if (!$user['estado']) {
			$this->error('El usuario ya se encuentra deshabilitado.');
		}

		$option = strtoupper($this->in(
			sprintf('¿Está seguro que desea deshabilitar el usuario (%d) %s?', $user['legajo'], $user['nombre_completo']),
			array('S', 'N'),
			'N'
		));
		if ($option === 'S') {
			$this->Usuario->id = $user['id'];
			$result = $this->Usuario->saveField('estado', false, false);
			if ($result) {
				$this->out('La operación solicitada se ha completado exitosamente.');
			} else {
				$this->error('No fue posible cambiar el estado del usuario debido a un error interno.');
			}
		}
	}

/**
 * Restablece la contraseña de un usuario
 *
 * Uso:
 * cake usuarios restablecer [-l]
 *
 * Opciones:
 *
 * --legajo, -l   Número de legajo
 *
 * @return void
 */
	public function restablecer() {
		$user = $this->_findUser($this->param('legajo'));
		if (!$user) {
			$this->error('El número de legajo especificado no es válido.');
		}
		$user = current($user);

		$option = strtoupper($this->in(
			sprintf(
				'¿Está seguro que desea restablecer la contraseña del usuario (%d) %s?',
				$user['legajo'],
				$user['nombre_completo']
			),
			array('S', 'N'),
			'N'
		));
		if ($option === 'S') {
			$result = $this->Usuario->save(
				array('id' => $user['id'], 'reset' => 1),
				array('fieldList' => array('id', 'password', 'reset'))
			);

			if ($result) {
				$this->out('La operación solicitada se ha completado exitosamente.');
			} else {
				$this->error('No fue posible restablecer la contraseña debido a un error interno.');
			}
		}
	}

/**
 * Busca un usuario por legajo
 *
 * @param int $legajo Número de legajo
 *
 * @return array|bool Datos del usuario o `false` si el número de legajo
 * no es válido o no se ha encontrado un registro
 */
	protected function _findUser($legajo) {
		if (empty($legajo) || !filter_var($legajo, FILTER_VALIDATE_INT)) {
			return false;
		}
		return $this->Usuario->findByLegajo($legajo);
	}

/**
 * getOptionParser
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$legajo = array(
			'short' => 'l',
			'help' => 'Número de legajo'
		);

		$parser = parent::getOptionParser();
		$parser->addSubcommand('habilitar', array(
			'help' => 'Habilita un usuario',
			'parser' => array(
				'options' => compact('legajo')
			)
		))->addSubcommand('deshabilitar', array(
			'help' => 'Deshabilita un usuario',
			'parser' => array(
				'options' => compact('legajo')
			)
		))->addSubcommand('restablecer', array(
			'help' => 'Restablece la contraseña de un usuario',
			'parser' => array(
				'options' => compact('legajo')
			)
		))->description(
			'Gestión de usuarios'
		);
		return $parser;
	}
}
