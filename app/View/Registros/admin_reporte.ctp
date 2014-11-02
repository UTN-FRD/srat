<?php
/**
 * Reporte
 *
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

/**
 * CSS
 */
$this->Html->css('reportes', array('inline' => false));

/**
 * JavaScript
 */
$this->Html->script('reportes', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Reportes');
?>
<?php echo $this->Form->create('Reporte', array('class' => 'form-horizontal')) ?>
<div class="help-box">
	<ul>
		<li>Puede utilizar el formulario ubicado a la izquierda para filtrar el resultado y generar condiciones más específicas.</li>
		<li>Recuerde actualizar la consulta antes de exportar el resultado para que todos los cambios sean tenidos en cuenta.</li>
		<li>La vista previa es aproximada y se encuentra dividida por páginas en esta vista sólo por conveniencia.</li>
		<li>Puede cambiar el orden del resultado haciendo clic en cada columna. El mismo será tenido en cuenta al exportar el resultado.</li>
		<li>Los campos seleccionados en el formulario serán persistidos hasta que haga clic en el botón Restablecer o cierre sesión.</li>
		<li>En caso que desee descargar un archivo en vez de visualizarlo, haga clic derecho en el botón Exportar resultado y luego en Guardar enlace como...</li>
	</ul>
</div>
<fieldset>
	<?php
	echo $this->Form->input('asignatura_id', array(
		'class' => 'combobox span8',
		'empty' => true
	));

	echo $this->Form->input('usuario_id', array(
		'class' => 'combobox span6',
		'empty' => true
	));

	$currentYear = date('Y');
	echo $this->Form->input('desde', array(
		'class' => 'datefield',
		'dateFormat' => 'DMY',
		'empty' => true,
		'maxYear' => $currentYear + 1,
		'minYear' => $currentYear - 1,
		'orderYear' => 'asc',
		'type' => 'date'
	));

	echo $this->Form->input('hasta', array(
		'class' => 'datefield',
		'dateFormat' => 'DMY',
		'empty' => true,
		'maxYear' => $currentYear + 1,
		'minYear' => $currentYear - 1,
		'orderYear' => 'asc',
		'type' => 'date'
	));

	echo $this->Form->input('tipo', array(
		'class' => 'span2',
		'default' => 1,
		'options' => array('Inasistencia', 'Asistencia', 'Ambos'),
		'type' => 'select'
	))
	?>
</fieldset>
<?php
echo $this->Form->buttons(array(
	'Actualizar consulta' => array('type' => 'submit'),
	'Exportar resultado' => array('class' => 'btn btn-info', 'target' => '_blank', 'url' => array('ext' => 'pdf')),
	'Restablecer' => array('url' => array('reset' => true))
));

/**
 * Cabeceras
 */
$headers = array(
	'#',
	$this->Paginator->sort('Materia.nombre', 'Asignatura'),
	$this->Paginator->sort('Usuario.legajo', 'Legajo'),
	$this->Paginator->sort('Usuario.apellido', 'Usuario'),
	$this->Paginator->sort('fecha', 'Fecha'),
	$this->Paginator->sort('entrada', 'Entrada'),
	$this->Paginator->sort('salida', 'Salida'),
	'Temas'
);

/**
 * Filas
 */
if (!empty($rows)):
	$start = $this->Paginator->counter(array('format' => '%start%'));
	foreach ($rows as $rid => $row):
		$asistencia = ($row['Registro']['tipo'] == '1');
		$rows[$rid] = array(
			$start++,
			h($row['Registro']['asignatura']),
			$row['Usuario']['legajo'],
			h(sprintf('%s, %s', $row['Usuario']['apellido'], $row['Usuario']['nombre'])),
			date('d/m/Y', strtotime($row['Registro']['fecha'])),
			($asistencia ? date('H:i', strtotime($row['Registro']['entrada'])) : '-'),
			($asistencia ? date('H:i', strtotime($row['Registro']['salida'])) : '-'),
			($asistencia ? nl2br(h($row['Registro']['obs'])) : '-')
		);
	endforeach;
endif;

/**
 * Tabla
 */
echo $this->element('table', array(
	'headers' => $headers,
	'rows' => $rows,
	'search' => false,
	'tasks' => false
));
