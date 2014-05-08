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
$this->Html->css('asignaturas', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Asignaturas');

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
	$this->Paginator->sort('Materia.nombre', 'Nombre'),
	$this->Paginator->sort('Area.nombre', 'Área'),
	$this->Paginator->sort('Nivel.nombre', 'Nivel'),
	$this->Paginator->sort('Tipo.nombre', 'Típo'),
	'Tareas'
);

/**
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$tasks = array(
			$this->Html->link('editar', array('action' => 'editar', $row['Asignatura']['id'])),
			$this->Form->postLink(
				'eliminar',
				array('action' => 'eliminar', $row['Asignatura']['id']),
				array('class' => 'delete', 'method' => 'delete')
			)
		);

		$rows[$rid] = array(
			$start++,
			h($row['Asignatura']['asignatura']),
			h($row['Area']['nombre']),
			h($row['Nivel']['nombre']),
			h($row['Tipo']['nombre']),
			implode(' ', $tasks)
		);
	endforeach;
endif;

/**
 * Tabla
 */
echo $this->element('table', compact('headers', 'rows'));
