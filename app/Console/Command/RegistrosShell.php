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
 * Registros
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class RegistrosShell extends AppShell {

/**
 * Modelos
 *
 * @var array
 */
	public $uses = array('Registro');

/**
 * Elimina registros
 *
 * Uso:
 * cake registros eliminar [-a] [-i] [-p]
 *
 * Opciones:
 *
 * --asistencia, -a    Registros de asistencia
 * --inasistencia, -i  Registros de inasistencia
 * --no-laborable, -p  Registros cuya fecha coincide con un día no laborable
 *
 * @return void
 */
	public function eliminar() {
		$conditions = array();
		foreach (array('inasistencia', 'asistencia', 'no-laborable') as $key => $type) {
			if ($this->param($type)) {
				$conditions[] = $key;
			}
		}
		if (empty($conditions)) {
			$this->error('Especifique al menos un tipo de registro.');
		}

		$option = strtoupper($this->in(
			'¿Está seguro que desea eliminar los registros?',
			array('S', 'N'),
			'N'
		));
		if ($option === 'S') {
			$result = false;
			if (count($conditions) == 3) {
				$result = $this->Registro->getDataSource()->truncate($this->Registro->useTable);
			} else {
				$this->Registro->unbindModel(array(
					'belongsTo' => array_keys($this->Registro->belongsTo),
					'hasOne' => array_keys($this->Registro->hasOne),
				));
				$result = $this->Registro->deleteAll(array('Registro.tipo' => $conditions), false, false);
			}
			if ($result) {
				$this->out('La operación solicitada se ha completado exitosamente.');
			} else {
				$this->error('La operación solicitada no se ha completado debido a un error interno.');
			}
		}
	}

/**
 * Trunca la tabla de registros
 *
 * Uso:
 * cake registros truncar
 *
 * @return void
 */
	public function truncar() {
		$option = strtoupper($this->in(
			'¿Está seguro que desea truncar la tabla de registros?',
			array('S', 'N'),
			'N'
		));
		if ($option === 'S') {
			$result = $this->Registro->getDataSource()->truncate($this->Registro->useTable);
			if ($result) {
				$this->out('La operación solicitada se ha completado exitosamente.');
			} else {
				$this->error('La operación solicitada no se ha completado debido a un error interno.');
			}
		}
	}

/**
 * getOptionParser
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->addSubcommand('eliminar', array(
			'help' => 'Elimina registros',
			'parser' => array(
				'options' => array(
					'asistencia' => array(
						'boolean' => true,
						'help' => 'Registros de asistencia',
						'short' => 'a'
					),
					'inasistencia' => array(
						'boolean' => true,
						'help' => 'Registros de inasistencia',
						'short' => 'i'
					),
					'no-laborable' => array(
						'boolean' => true,
						'help' => 'Registros cuya fecha coincide con un día no laborable',
						'short' => 'p'
					)
				)
			)
		))->addSubcommand('truncar', array(
			'help' => 'Trunca la tabla de registros'
		))->description(
			'Gestión de registros'
		);
		return $parser;
	}
}
