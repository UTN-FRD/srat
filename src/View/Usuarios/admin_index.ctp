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
$this->Html->css('usuarios', array('inline' => false));

/**
 * Breadcrumbs
 */
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
	$this->Paginator->sort('Rol.nombre', 'Rol'),
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

		$rows[$rid] = array(
			$start++,
			$row['Usuario']['legajo'],
			h($row['Usuario']['apellido']),
			h($row['Usuario']['nombre']),
			$status[$row['Usuario']['estado']],
			h($row['Rol']['nombre']),
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
