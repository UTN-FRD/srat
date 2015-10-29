<?php
/**
 * Índice
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
$this->Html->css('horarios', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Administrar');
$this->Html->addCrumb('Horarios');

/**
 * Tareas
 */
$this->set('tasks', array(
	array(
		'text' => 'Agregar',
		'url' => array(
			'action' => 'agregar'
		)
	)
));

/**
 * Cabeceras
 */
$headers = array(
	'#',
	$this->Paginator->sort('Materia.nombre', 'Asignatura'),
	$this->Paginator->sort('dia', 'Día'),
	$this->Paginator->sort('entrada', 'Entrada'),
	$this->Paginator->sort('salida', 'Salida'),
	'Tareas'
);

/**
 * Filas
 */
if (!empty($rows)):
	$days = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$tasks = array(
			$this->Html->link('editar', array('action' => 'editar', $row['Horario']['id'])),
			$this->Form->postLink(
				'eliminar',
				array('action' => 'eliminar', $row['Horario']['id']),
				array('class' => 'delete', 'method' => 'delete')
			)
		);

		$rows[$rid] = array(
			$start++,
			h($row['Horario']['asignatura']),
			$days[$row['Horario']['dia']],
			date('H:i', strtotime($row['Horario']['entrada'])),
			date('H:i', strtotime($row['Horario']['salida'])),
			implode(' ', $tasks)
		);
	endforeach;
endif;

/**
 * Tabla
 */
echo $this->element('table', compact('headers', 'rows'));
