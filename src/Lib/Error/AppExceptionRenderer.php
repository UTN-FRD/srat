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
App::uses('ExceptionRenderer', 'Error');

/**
 * Renderizador de excepciones de la aplicación
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class AppExceptionRenderer extends ExceptionRenderer {

/**
 * Página no encontrada
 *
 * @param Exception $e Excepción
 *
 * @return void
 */
	public function error400($e) {
		$this->controller->set(array(
			'title_for_layout' => 'Página no encontrada',
			'title_for_view' => 'Página no encontrada'
		));

		parent::error400($e);
	}

/**
 * Página no disponible
 *
 * @param Exception $e Excepción
 *
 * @return void
 */
	public function error500($e) {
		$this->controller->set(array(
			'title_for_layout' => 'Página no disponible',
			'title_for_view' => 'Página no disponible'
		));

		parent::error500($e);
	}

/**
 * Genera la respuesta utilizando el objeto controlador
 *
 * @param string $template Plantilla
 *
 * @return void
 */
	protected function _outputMessage($template) {
		if (ob_get_level() > 1) {
			ob_end_clean();
		}

		parent::_outputMessage($template);
	}
}
