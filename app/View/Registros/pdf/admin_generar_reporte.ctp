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
<div class="report-builder">
	<table class="table table-condensed report-details">
		<tbody>
			<?php if (!empty($data['asignatura'])): ?>
				<tr>
					<td>Asignatura:</td>
					<td><?php echo h($data['asignatura']) ?></td>
				</tr>
			<?php endif ?>

			<?php if (!empty($data['usuario'])): ?>
				<tr>
					<td>Usuario:</td>
					<td><?php echo h($data['usuario']) ?></td>
				</tr>
			<?php endif ?>

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
		$rows = array_chunk($rows, 10);
		$chunks = count($rows);
		if ($chunks):
			foreach ($rows as $cid => $chunk):
				if ($cid > 0):
					echo '<br />';
				endif;

				echo $this->element(
					'Report/table',
					compact('chunk', 'data')
				);

				if ($cid < ($chunks - 1)):
					echo '<div class="page-break"></div>';
				endif;
			endforeach;
		else:
			echo $this->element('Report/table');
		endif;
		?>
	</div>
</div>
