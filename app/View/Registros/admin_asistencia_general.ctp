<?php
/**
 * Asistencia general
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
$this->Html->css('reportes', array('inline' => false));

/**
 * JavaScript
 */
$this->Html->script(array('reportes', 'stupidtable.min'), array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Reportes');
$this->Html->addCrumb('Asistencia general');
?>
<div class="report-container report-general">
	<?php echo $this->Form->create('Reporte', array('class' => 'form-horizontal report-filter')) ?>
	<div class="howto">
		<ul>
			<li>Puede utilizar el formulario ubicado a la izquierda para filtrar el resultado y generar condiciones más específicas.</li>
			<li>Recuerde actualizar la consulta antes de exportar el resultado para que todos los cambios sean tenidos en cuenta.</li>
			<li>Los campos seleccionados en el formulario serán persistidos hasta que haga clic en el botón Restablecer o cierre sesión.</li>
			<li>En caso que desee descargar un archivo en vez de visualizarlo, haga clic derecho en el botón Exportar resultado y luego en Guardar enlace como...</li>
		</ul>
	</div>
	<fieldset>
		<?php
		echo $this->Form->input('carrera_id', array(
			'class' => 'span5',
			'default' => key($carreras),
			'locked' => (count($carreras) == 1)
		));

		$currentYear = date('Y');
		echo $this->Form->input('desde', array(
			'class' => 'field-date',
			'dateFormat' => 'DMY',
			'empty' => true,
			'maxYear' => $currentYear,
			'minYear' => $currentYear - 1,
			'orderYear' => 'asc',
			'type' => 'date'
		));

		echo $this->Form->input('hasta', array(
			'class' => 'field-date',
			'dateFormat' => 'DMY',
			'empty' => true,
			'maxYear' => $currentYear,
			'minYear' => $currentYear - 1,
			'orderYear' => 'asc',
			'type' => 'date'
		));
		?>
	</fieldset>
	<?php
	echo $this->Form->buttons(array(
		'Actualizar consulta' => array('type' => 'submit'),
		'Exportar resultado' => array('class' => 'btn btn-info', 'target' => '_blank', 'url' => array('ext' => 'pdf')),
		'Restablecer' => array('url' => array('reset' => true))
	));

	$headers = array(
		'Materia',
		'Docente',
		'Asistencias',
		'Inasistencias',
		'Días sin actividad'
	);

	if (!empty($rows)):
		$records = array();
		foreach($rows as $row):
			$records[$row['Materia']['nombre']][$row['Registro']['usuario']] = $row[0];
		endforeach;
		ksort($records);
		foreach ($records as &$record):
			ksort($record);
		endforeach;

		$rows = array();
		foreach ($records as $materia => $docentes):
			foreach ($docentes as $docente => $values):
				$rows[] = array_merge(
					array(h($materia), h($docente)),
					$values
				);
			endforeach;
		endforeach;
	endif;

	echo $this->element('table', array(
		'class' => 'report-preview',
		'headers' => $headers,
		'rows' => $rows,
		'pager' => false,
		'search' => false,
		'tasks' => false
	));
	?>
</div>
