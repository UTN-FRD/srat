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
 * InflectionTest
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class InflectionTest extends CakeTestCase {

/**
 * testPlurals
 *
 * @return void
 */
	public function testPlurals() {
		$this->assertEquals(Inflector::pluralize('Análisis'), 'Análisis');
		$this->assertEquals(Inflector::pluralize('Asignatura'), 'Asignaturas');
		$this->assertEquals(Inflector::pluralize('Carrera'), 'Carreras');
		$this->assertEquals(Inflector::pluralize('Materia'), 'Materias');
		$this->assertEquals(Inflector::pluralize('Modelos'), 'Modelos');
		$this->assertEquals(Inflector::pluralize('Perfil'), 'Perfiles');
		$this->assertEquals(Inflector::pluralize('Rol'), 'Roles');
		$this->assertEquals(Inflector::pluralize('Usuario'), 'Usuarios');
	}

/**
 * testSingulars
 *
 * @return void
 */
	public function testSingulars() {
		$this->assertEquals(Inflector::singularize('Análisis'), 'Análisis');
		$this->assertEquals(Inflector::singularize('Asignaturas'), 'Asignatura');
		$this->assertEquals(Inflector::singularize('Carreras'), 'Carrera');
		$this->assertEquals(Inflector::singularize('Materias'), 'Materia');
		$this->assertEquals(Inflector::singularize('Modelo'), 'Modelo');
		$this->assertEquals(Inflector::singularize('Perfiles'), 'Perfil');
		$this->assertEquals(Inflector::singularize('Roles'), 'Rol');
		$this->assertEquals(Inflector::singularize('Usuarios'), 'Usuario');
	}
}
