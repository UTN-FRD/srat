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
$this->Html->css('inasistencias', array('inline' => false));

/**
 * JavaScript
 */
$this->Html->script('inasistencias', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Inasistencias');

/**
 * Tareas
 */
$this->set('tasks', array(
	array(
		'attribs' => array('class' => 'disabled'),
		'options' => array('class' => 'edit-link', 'escape' => false),
		'text' => 'Editar <span class="check-counter">0</span> registro(s)',
		'url' => array(
			'action' => 'editar_inasistencia'
		)
	)
));

/**
 * Cabeceras
 */
$headers = array(
	'#',
	'<input type="checkbox" />',
	$this->Paginator->sort('Materia.nombre', 'Asignatura'),
	$this->Paginator->sort('Usuario.legajo', 'Legajo'),
	$this->Paginator->sort('Usuario.apellido', 'Docente'),
	$this->Paginator->sort('fecha', 'Fecha'),
	'Observaciones'
);

/**
 * Comienzo del formulario
 */
$formStart = $this->Form->create('Inasistencia', array(
	'class' => 'form-inasistencia',
	'url' => array('action' => 'editar')
));

/**
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$rows[$rid] = array(
			$start++,
			$this->Form->checkbox(sprintf('Inasistencia.id.%d', $row['Inasistencia']['id'])),
			str_replace(':', ':<br />', h($row['Inasistencia']['asignatura'])),
			$row['Usuario']['legajo'],
			h($row['Inasistencia']['docente']),
			date('d/m/Y', strtotime($row['Inasistencia']['fecha'])),
			nl2br(h($row['Inasistencia']['obs']))
		);
	endforeach;
endif;

/**
 * Fin del formulario
 */
$formEnd = $this->Form->end();

/**
 * Tabla
 */
echo $this->element('table', array(
	'beforeTable' => $formStart,
	'afterTable' => $formEnd,
	'headers' => $headers,
	'rows' => $rows,
	'tasks' => false
));
