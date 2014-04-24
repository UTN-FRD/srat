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
App::uses('FormHelper', 'View/Helper');

/**
 * Simplifica la construcción de elementos HTML
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class MyFormHelper extends FormHelper {

/**
 * Obtiene el mensaje de error para el campo de un formulario
 *
 * @param string $field Nombre del campo
 * @param string|array $text Mensaje de error
 * @param array $options Opciones de renderizado
 *
 * @return string Mensaje de error o `null` si no hay error
 */
	public function error($field, $text = null, $options = []) {
		return parent::error($field, $text, array_merge(
			array('class' => 'help-inline', 'escape' => true, 'wrap' => 'span'),
			$options
		));
	}

/**
 * Genera un elemento para un formulario
 *
 * @param string $field Nombre del campo
 * @param array $options Opciones
 *
 * @return string Elemento
 */
	public function input($fieldName, $options = []) {
		if (isset($options['after'])) {
			$options['after'] = '<p class="help-block">' . $options['after'] . '</p></div>';
		} else {
			$options['after'] = '</div>';
		}

		if (!isset($options['between'])) {
			$options['between'] = '<div class="controls">';
		} else {
			$options['between'] = '<div class="controls">' . $options['between'];
		}

		if (!isset($options['div']['class'])) {
			$options['div']['class'] = 'control-group';
		} else {
			if (is_array($options['div']['class'])) {
				$options['div']['class'] = implode(' ', $options['div']['class']);
			}
			$options['div']['class'] .= ' control-group';
		}

		if (!isset($options['format'])) {
			$options['format'] = array('before', 'label', 'between', 'input', 'error', 'after');
		}

		return parent::input($fieldName, $options);
	}

/**
 * Genera un elemento label
 *
 * @param string $field Nombre del campo
 * @param string $text Texto a mostrar en el campo
 * @param array|string $options Un `array` con atributos HTML, o `string`,
 * para ser usada como clase del elemento
 *
 * @return string Elemento label
 */
	public function label($fieldName = null, $text = null, $options = []) {
		if (is_string($options)) {
			$options = array('class' => $options);
		}

		if (isset($options['class'])) {
			$options['class'] .= ' control-label';
		} else {
			$options['class'] = 'control-label';
		}

		return parent::label($fieldName, $text, $options);
	}
}
