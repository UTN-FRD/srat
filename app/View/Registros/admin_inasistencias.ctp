<?php
/**
 * Inasistencias
 *
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
 * CSS
 */
$this->Html->css('inasistencias', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Inasistencias');

/**
 * Cabeceras
 */
$headers = array(
	'#',
	$this->Paginator->sort('Materia.nombre', 'Asignatura'),
	$this->Paginator->sort('Usuario.legajo', 'Legajo'),
	$this->Paginator->sort('Usuario.apellido', 'Usuario'),
	$this->Paginator->sort('fecha', 'Fecha'),
	'Observaciones'
);

/**
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$rows[$rid] = array(
			$start++,
			str_replace(':', ':<br />', h($row['Registro']['asignatura'])),
			$row['Usuario']['legajo'],
			h(sprintf('%s, %s', $row['Usuario']['apellido'], $row['Usuario']['nombre'])),
			date('d/m/Y', strtotime($row['Registro']['fecha'])),
			nl2br(h($row['Registro']['obs']))
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
