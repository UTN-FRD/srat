<?php
/**
 * Docentes
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
$this->Html->addCrumb('Docentes');

/**
 * Cabeceras
 */
$headers = array(
	'#',
	$this->Paginator->sort('legajo', 'Legajo'),
	$this->Paginator->sort('apellido', 'Apellido'),
	$this->Paginator->sort('nombre', 'Nombre')
);

/**
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$rows[$rid] = array(
			$start++,
			$row['Usuario']['legajo'],
			h($row['Usuario']['apellido']),
			h($row['Usuario']['nombre'])
		);
	endforeach;
endif;
?>
<p>
	La siguiente tabla lista todos los docentes registrados en el sistema.
	<br />
	En caso que usted no se encuentre registrado o que sus datos no sean correctos, por favor, contacte al administrador.
</p>
<?php
echo $this->element('table', array(
	'class' => 'usuarios',
	'headers' => $headers,
	'rows' => $rows,
	'tasks' => false
));
