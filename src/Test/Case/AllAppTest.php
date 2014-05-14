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
 * AllAppTest
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class AllAppTest extends PHPUnit_Framework_TestSuite {

/**
 * suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All App tests');

		$path = dirname(__FILE__);
		$suite->addTestDirectory($path . DS . 'I18n');

		return $suite;
	}
}
