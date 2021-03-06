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
$this->Html->css('cargos', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Administrar');
$this->Html->addCrumb('Cargos');

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
	$this->Paginator->sort('Usuario.nombre', 'Docente'),
	$this->Paginator->sort('Grado.nombre', 'Grado'),
	$this->Paginator->sort('dedicacion', 'Dedicación'),
	$this->Paginator->sort('resolucion', 'Resolución'),
	'Tareas'
);

/**
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$tasks = array(
			$this->Html->link('editar', array('action' => 'editar', $row['Cargo']['id'])),
			$this->Form->postLink(
				'eliminar',
				array('action' => 'eliminar', $row['Cargo']['id']),
				array('class' => 'delete', 'method' => 'delete')
			)
		);

		$rows[$rid] = array(
			$start++,
			str_replace(':', ':<br />', h($row['Cargo']['asignatura'])),
			h($row['Cargo']['docente']),
			sprintf('%s<br />(%s)', h($row['Grado']['nombre']), h($row['Tipo']['nombre'])),
			sprintf('%g<br />(%s)', round($row['Cargo']['dedicacion'], 1), h($row['Dedicacion']['nombre'])),
			$row['Cargo']['resolucion'],
			implode(' ', $tasks)
		);
	endforeach;
endif;

/**
 * Tabla
 */
echo $this->element('table', compact('headers', 'rows'));
