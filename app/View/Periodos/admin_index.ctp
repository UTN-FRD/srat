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
$this->Html->css('periodos', array('inline' => false));

/**
 * Scripts
 */
$this->Html->script('periodos', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Administrar');
$this->Html->addCrumb('Períodos no laborables');

/**
 * Tareas
 */
$this->set('tasks', array(
	array(
		'text' => 'Agregar',
		'url' => array(
			'action' => 'agregar'
		)
	),
	array(
		'text' => 'Importar',
		'attribs' => array('class' => 'upload-link'),
		'url' => array(
			'action' => 'importar'
		)
	),
	array(
		'text' => 'Exportar',
		'url' => array(
			'action' => 'exportar'
		)
	)
));

/**
 * Cabeceras
 */
$headers = array(
	'#',
	$this->Paginator->sort('desde', 'Desde'),
	$this->Paginator->sort('hasta', 'Hasta'),
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
			$this->Html->link('editar', array('action' => 'editar', $row['Periodo']['id'])),
			$this->Form->postLink(
				'eliminar',
				array('action' => 'eliminar', $row['Periodo']['id']),
				array('class' => 'delete', 'method' => 'delete')
			)
		);

		$rows[$rid] = array(
			$start++,
			date('d/m/Y', strtotime($row['Periodo']['desde'])),
			date('d/m/Y', strtotime($row['Periodo']['hasta'])),
			nl2br(h($row['Periodo']['obs'])),
			implode(' ', $tasks)
		);
	endforeach;
endif;

/**
 * Tabla
 */
echo $this->element('table', compact('headers', 'rows'));

/**
 * Importar períodos
 */
echo $this->Form->create('Periodo', array('type' => 'file', 'url' => array('action' => 'importar')));
echo $this->Form->file('archivo');
echo $this->Form->end();
