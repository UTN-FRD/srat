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
 * UsuarioFixture
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class UsuarioFixture extends CakeTestFixture {

/**
 * Importación
 *
 * @var array
 */
	public $import = array('model' => 'Usuario');

/**
 * Registros
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'rol_id' => '1',
			'legajo' => '1',
			'password' => '$2a$10$JTFmlyWPAXBXVh.NW0azOuU1WvwL/W0q2vRQum7vM645Ote/Cy8Oq',
			'reset' => 0,
			'estado' => 1,
			'apellido' => '-',
			'nombre' => 'Administrador'
		),
		array(
			'id' => '2',
			'rol_id' => '2',
			'legajo' => '22540',
			'password' => '$2a$10$3KWHDZfLKpVkPv0UOD0abuDxlZ2VdzmvJWiFEWz4PHyUhILDqqG9e',
			'reset' => 0,
			'estado' => 1,
			'apellido' => 'Asís',
			'nombre' => 'Pedro Sebastián'
		),
		array(
			'id' => '3',
			'rol_id' => '2',
			'legajo' => '14160',
			'password' => '$2a$10$HFLuPycEGh.tEatiQerasOstwWOkBff.NKxIS7rADxusIG3wv87T6',
			'reset' => 0,
			'estado' => 1,
			'apellido' => 'Bettinoti',
			'nombre' => 'Adriana'
		),
		array(
			'id' => '4',
			'rol_id' => '2',
			'legajo' => '36618',
			'password' => '$2a$10$.dcO4ex3qtJUBmBcen0P0OOeZbXWui6WsJA8KI.Q1wAJLbshVGxUa',
			'reset' => 1,
			'estado' => 1,
			'apellido' => 'Baroni',
			'nombre' => 'Nombre'
		),
		array(
			'id' => '5',
			'rol_id' => '2',
			'legajo' => '65811',
			'password' => '$2a$10$M1k.KtNLHW8cyvGzDRF6peuWucPhbU/4OOliSaWbegOJmcRHImWW6',
			'reset' => 1,
			'estado' => 0,
			'apellido' => 'Brizuela',
			'nombre' => 'Nombre'
		)
	);
}
