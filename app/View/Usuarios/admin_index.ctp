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
$this->Html->css('usuarios', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Sistema');
$this->Html->addCrumb('Usuarios');

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
	$this->Paginator->sort('legajo', 'Legajo'),
	$this->Paginator->sort('apellido', 'Apellido'),
	$this->Paginator->sort('nombre', 'Nombre'),
	$this->Paginator->sort('estado', 'Estado'),
	$this->Paginator->sort('admin', 'Administrador'),
	'Tareas'
);

/**
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	$status = array('Deshabilitado', 'Habilitado');
	foreach ($rows as $rid => $row):
		$tasks = array(
			$this->Html->link('editar', array('action' => 'editar', $row['Usuario']['id']))
		);
		if ($row['Usuario']['id'] > 1):
			$tasks[] = $this->Form->postLink(
				'eliminar',
				array('action' => 'eliminar', $row['Usuario']['id']),
				array('class' => 'delete', 'method' => 'delete')
			);
		endif;

		$rows[$rid] = array(
			$start++,
			$row['Usuario']['legajo'],
			h($row['Usuario']['apellido']),
			h($row['Usuario']['nombre']),
			$status[$row['Usuario']['estado']],
			((bool)$row['Usuario']['admin'] ? 'Si' : 'No'),
			implode(' ', $tasks)
		);
	endforeach;
endif;

/**
 * Tabla
 */
echo $this->element('table', array(
	'class' => 'usuarios',
	'headers' => $headers,
	'rows' => $rows
));
