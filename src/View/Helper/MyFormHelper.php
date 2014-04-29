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
	public function error($field, $text = null, $options = array()) {
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
	public function input($fieldName, $options = array()) {
		if (isset($options['after'])) {
			$helpClass = 'help-block';
			if (isset($options['type']) && $options['type'] === 'checkbox') {
				$helpClass = 'help-inline';
			}
			$options['after'] = '<p class="' . $helpClass . '">' . $options['after'] . '</p></div>';
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

		if (isset($options['type']) && $options['type'] === 'checkbox') {
			$options['div']['class'] .= ' checkbox-control';
		}

		if (!isset($options['format'])) {
			$options['format'] = array('before', 'label', 'between', 'input', 'error', 'after');
		}

		$output = '';
		if (!empty($options['locked'])) {
			unset($options['locked']);
			$options += array(
				'disabled' => true,
				'secure' => self::SECURE_SKIP
			);
			$output = $this->hidden($fieldName, array('id' => false));
		}

		return $output . parent::input($fieldName, $options);
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
	public function label($fieldName = null, $text = null, $options = array()) {
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

/**
 * Genera uno o más elementos button
 *
 * Cada clave de la matriz `$buttons` se utiliza como texto del botón.
 *
 * ### Opciones
 *
 * - (boolean) `condition`
 * Un valor verdadero determina si se procesa el botón.
 *
 * - (string) `type`
 * Tipo de bótón.
 *
 * - (string|array) `url`
 * Convierte el botón en un enlace.
 *
 * @param array $buttons Botones
 * @param boolean $close Indica si se debe cerrar el formulario utilizando
 * el método `MyFormHelper::end()`
 *
 * @return string Uno o más elementos button dentro de un elemento DIV y/o el
 * cierre del formulario si `$close` es verdadero. Si no hay botones y
 * `$close` es falso, una cadena vacía.
 */
	public function buttons($buttons, $close = true) {
		$out = '';
		if (!empty($buttons)) {
			foreach ($buttons as $label => $options) {
				if (isset($options['condition'])) {
					if ($options['condition'] === false) {
						continue;
					}
					unset($options['condition']);
				}

				if (!isset($options['class'])) {
					$options['class'] = 'btn';
				}

				if (isset($options['type'])) {
					if ($options['type'] === 'submit') {
						$options['class'] .= ' btn-primary';
					} elseif ($options['type'] === 'refresh') {
						$options['class'] .= ' refresh';
						$options['type'] = 'button';
						$this->unlockField('refresh');
					}
				}

				if (!isset($options['url'])) {
					$out .= $this->button(h($label), $options) . PHP_EOL;
				} else {
					$url = $options['url'];
					unset($options['url']);
					$out .= $this->Html->link($label, $url, $options) . PHP_EOL;
				}
			}

			if (!empty($out)) {
				$out = $this->Html->tag('div', $out, array('class' => 'form-actions'));
			}
		}

		if ($close) {
			$out .= $this->end();
		}
		return $out;
	}
}
