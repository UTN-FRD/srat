<?php
/**
 * Índice
 *
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
 * CSS
 */
$this->Html->css('asistencias', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Asistencias');

/**
 * Cabeceras
 */
$headers = array(
	'#',
	$this->Paginator->sort('Materia.nombre', 'Asignatura'),
	$this->Paginator->sort('Usuario.nombre', 'Usuario'),
	$this->Paginator->sort('fecha', 'Fecha'),
	$this->Paginator->sort('entrada', 'Entrada'),
	$this->Paginator->sort('salida', 'Salida'),
	'Temas'
);

/**
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$rows[$rid] = array(
			$start++,
			h($row['Asistencia']['asignatura']),
			h($row['Asistencia']['usuario']),
			date('d-m-Y', strtotime($row['Asistencia']['fecha'])),
			date('H:i', strtotime($row['Asistencia']['entrada'])),
			date('H:i', strtotime($row['Asistencia']['salida'])),
			nl2br(h($row['Asistencia']['obs']))
		);
	endforeach;
endif;

/**
 * Tabla
 */
echo $this->element('table', array(
	'headers' => $headers,
	'rows' => $rows,
	'tasks' => false
));
