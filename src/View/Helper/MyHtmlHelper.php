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
App::uses('HtmlHelper', 'View/Helper');

/**
 * Simplifica la construcción de elementos HTML
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class MyHtmlHelper extends HtmlHelper {

/**
 * Procesa enlaces para una lista HTML
 *
 * ### Opciones del enlace
 *
 * - (array) `attribs`
 * Opciones para el elemento LI pasadas a `MyHtmlHelper::tag()`
 *
 * - (boolean) `condition`
 * El enlace no se procesa si el valor no es verdadero
 *
 * - (boolean) `divider`
 * Indica que el elemento es un divisor y no un enlace
 *
 * - (array) `dropdown`
 * Uno o más enlaces a mostrar en un menú desplegable
 *
 * - (boolean) `header`
 * Indica que el elemento es una cabecera y no un enlace
 *
 * - (array) `options`
 * Opciones para el enlace pasadas a `MyHtmlHelper::link()`
 *
 * - (string) `text`
 * Texto del enlace
 *
 * - (array|string) `url`
 * Dirección URL
 *
 * @param array $links Enlaces
 *
 * @return string Cada enlace dentro de un elemento LI o una cadena vacía en caso contrario
 */
	public function parseLinkList($links) {
		static $depth = 0;
		$depth++;

		$out = '';
		if (!empty($links)) {
			foreach ($links as $link) {
				if (!is_array($link)) {
					continue;
				}

				$link += array(
					'attribs' => array(),
					'condition' => true,
					'divider' => false,
					'dropdown' => array(),
					'header' => false,
					'options' => array(),
					'text' => null,
					'url' => '#'
				);

				if (!$link['condition']) {
					continue;
				} elseif ($link['divider'] === true) {
					$link['divider'] = 'divider';
					if ($depth <= 1) {
						$link['divider'] .= '-vertical';
					}
					$out .= $this->tag('li', '', array('class' => $link['divider']));
				} elseif ($link['header'] === true && $link['text']) {
					$out .= $this->tag('li', h($link['text']), array('class' => 'nav-header'));
				} else {
					if (!$link['text'] || (!$link['url'] && !$link['dropdown'])) {
						continue;
					}

					if ($depth > 1 || !$link['dropdown']) {
						$link['dropdown'] = false;
					} else {
						$link['dropdown'] = $this->parseLinkList($link['dropdown']);
						if ($link['dropdown']) {
							$link['dropdown'] = $this->tag('ul', $link['dropdown'], array('class' => 'dropdown-menu'));

							if (!isset($link['attribs']['class'])) {
								$link['attribs']['class'] = 'dropdown';
							} else {
								$link['attribs']['class'] .= ' dropdown';
							}

							if (!isset($link['options']['class'])) {
								$link['options']['class'] = 'dropdown-toggle';
							} else {
								$link['options']['class'] .= ' dropdown-toggle';
							}

							$link['options']['data-toggle'] = 'dropdown';
							$link['url'] = '#';
						}
					}

					if ($link['dropdown']) {
						if (!isset($link['options']['escape'])) {
							$link['options']['escape'] = true;
						}

						if ($link['options']['escape']) {
							$link['text'] = h($link['text']);
						}
						$link['options']['escape'] = false;

						if ($link['dropdown']) {
							$link['text'] .= ' ' . $this->tag('b', '', array('class' => 'caret'));
						}
					}

					$link['text'] = $this->link($link['text'], $link['url'], $link['options']);
					if ($link['dropdown']) {
						$link['text'] .= $link['dropdown'];
					}

					$out .= $this->tag('li', $link['text'], $link['attribs']);
				}
			}
		}

		$depth--;
		return $out;
	}

/**
 * Genera una lista HTML de enlaces
 *
 * @param array $links Enlaces
 * @param array $options Opciones para el elemento UL pasadas a `MyHtmlHelper::tag()`
 *
 * @return string Lista HTML de enlaces o una cadena vacía en caso contrario
 */
	public function generateLinkList($links, $options = array()) {
		$out = $this->parseLinkList($links);
		if ($out) {
			$out = $this->tag('ul', $out, $options);
		}
		return $out;
	}
}
