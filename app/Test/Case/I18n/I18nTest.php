<?php
/**
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

/**
 * Dependencias
 */
App::uses('CakeTime', 'Utility');
App::uses('I18n', 'I18n');

/**
 * I18nTest
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class I18nTest extends CakeTestCase {

/**
 * testDays
 *
 * @return void
 */
	public function testDays() {
		$this->assertEquals(I18n::translate('Sunday', null, 'cake'), 'Domingo');
		$this->assertEquals(I18n::translate('Monday', null, 'cake'), 'Lunes');
		$this->assertEquals(I18n::translate('Tuesday', null, 'cake'), 'Martes');
		$this->assertEquals(I18n::translate('Wednesday', null, 'cake'), 'Miércoles');
		$this->assertEquals(I18n::translate('Thursday', null, 'cake'), 'Jueves');
		$this->assertEquals(I18n::translate('Friday', null, 'cake'), 'Viernes');
		$this->assertEquals(I18n::translate('Saturday', null, 'cake'), 'Sábado');
	}

/**
 * testMonths
 *
 * @return void
 */
	public function testMonths() {
		$this->assertEquals(I18n::translate('January', null, 'cake'), 'Enero');
		$this->assertEquals(I18n::translate('February', null, 'cake'), 'Febrero');
		$this->assertEquals(I18n::translate('March', null, 'cake'), 'Marzo');
		$this->assertEquals(I18n::translate('April', null, 'cake'), 'Abril');
		$this->assertEquals(I18n::translate('May', null, 'cake'), 'Mayo');
		$this->assertEquals(I18n::translate('June', null, 'cake'), 'Junio');
		$this->assertEquals(I18n::translate('July', null, 'cake'), 'Julio');
		$this->assertEquals(I18n::translate('August', null, 'cake'), 'Agosto');
		$this->assertEquals(I18n::translate('September', null, 'cake'), 'Septiembre');
		$this->assertEquals(I18n::translate('October', null, 'cake'), 'Octubre');
		$this->assertEquals(I18n::translate('November', null, 'cake'), 'Noviembre');
		$this->assertEquals(I18n::translate('December', null, 'cake'), 'Diciembre');
	}

/**
 * testLocale
 *
 * @return void
 */
	public function testLocale() {
		$this->assertEquals(CakeTime::format("01/05/2014", '%A'), 'domingo');
		$this->assertEquals(CakeTime::format("01/06/2014", '%A'), 'lunes');
		$this->assertEquals(CakeTime::format("01/07/2014", '%A'), 'martes');
		$this->assertEquals(CakeTime::format("01/08/2014", '%A'), 'miércoles');
		$this->assertEquals(CakeTime::format("01/09/2014", '%A'), 'jueves');
		$this->assertEquals(CakeTime::format("01/10/2014", '%A'), 'viernes');
		$this->assertEquals(CakeTime::format("01/11/2014", '%A'), 'sábado');
	}
}
