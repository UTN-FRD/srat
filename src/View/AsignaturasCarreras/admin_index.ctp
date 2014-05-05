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
 * Breadcrumbs
 */
$this->Html->addCrumb('Asignaturas', array('controller' => 'asignaturas'));
$this->Html->addCrumb('Carreras');

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
	$this->Paginator->sort('nombre', 'Nombre'),
	$this->Paginator->sort('obs', 'Descripción'),
	'Tareas'
);

/**
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$tasks = array(
			$this->Html->link('editar', array('action' => 'editar', $row['AsignaturasCarrera']['id'])),
			$this->Form->postLink(
				'eliminar',
				array('action' => 'eliminar', $row['AsignaturasCarrera']['id']),
				array('class' => 'delete', 'method' => 'delete')
			)
		);

		$rows[$rid] = array(
			$start++,
			h($row['AsignaturasCarrera']['nombre']),
			nl2br(h($row['AsignaturasCarrera']['obs'])),
			implode(' ', $tasks)
		);
	endforeach;
endif;

/**
 * Tabla
 */
echo $this->element('table', compact('headers', 'rows'));
