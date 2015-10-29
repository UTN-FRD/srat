<?php
/**
 * Reporte de asistencia y/o inasistencia
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
$this->Html->css(
	array('bootstrap.min', 'reportes'),
	array('fullBase' => true, 'inline' => false)
);
?>
<div class="report-general">
	<table class="table table-condensed report-details">
		<tbody>
			<tr>
				<td>Carrera:</td>
				<td><?php echo h($carreras[$data['carrera_id']]) ?></td>
			</tr>

			<?php if (!empty($data['desde'])): ?>
				<tr>
					<td>Desde:</td>
					<td><?php echo date('d/m/Y', strtotime($data['desde'])) ?></td>
				</tr>
			<?php endif ?>

			<?php if (!empty($data['hasta'])): ?>
				<tr>
					<td>Hasta:</td>
					<td><?php echo date('d/m/Y', strtotime($data['hasta'])) ?></td>
				</tr>
			<?php endif ?>
		</tbody>
	</table>

	<div class="report-container">
		<?php
		$records = array();
		foreach($rows as $row):
			$records[$row['Materia']['nombre']][$row['Registro']['usuario']] = $row[0];
		endforeach;
		unset($rows, $row);
		ksort($records);
		foreach ($records as &$record):
			ksort($record);
		endforeach;

		$pushRow = function ($row) use (&$rows) {
			if (empty($rows)):
				$rows[] = array();
			endif;
			$index = count($rows) - 1;
			if (count($rows[$index]) == 20):
				$rows[] = array();
				$index++;
			endif;
			$rows[$index][] = $row;
		};

		$rows = array();
		foreach ($records as $materia => $docentes):
			foreach ($docentes as $docente => $values):
				$pushRow(array_merge(
					array(h($materia), h($docente)),
					$values
				));
			endforeach;
		endforeach;

		$count = count($rows);
		foreach ($rows as $rid => $chunk):
			if ($rid > 0):
				echo '<br />';
			endif;

			echo $this->element(
				'Report/table_general',
				compact('chunk')
			);

			if ($rid < ($count - 1)):
				echo '<div class="page-break"></div>';
			endif;
		endforeach;
		?>
	</div>
</div>
