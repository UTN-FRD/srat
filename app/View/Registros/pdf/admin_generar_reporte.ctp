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
?>
<table class="details">
	<tbody>
		<tr>
			<td>
				<div>
					<span>Asignatura:</span>
					<?php if (!empty($data['asignatura'])): ?>
						<?php echo h($data['asignatura']) ?>
					<?php else: ?>
						-
					<?php endif ?>
				</div>
				<div>
					<span>Desde:</span>
					<?php if (!empty($data['desde'])): ?>
						<?php echo date('d/m/Y', strtotime($data['desde'])) ?>
					<?php else: ?>
						-
					<?php endif ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div>
					<span>Docente:</span>
					<?php if (!empty($data['usuario'])): ?>
						<?php echo h($data['usuario']) ?>
					<?php else: ?>
						-
					<?php endif ?>
				</div>
				<div>
					<span>Hasta:</span>
					<?php if (!empty($data['hasta'])): ?>
						<?php echo date('d/m/Y', strtotime($data['hasta'])) ?>
					<?php else: ?>
						<?php echo date('d/m/Y', time()) ?>
					<?php endif ?>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<?php
$rows = array_chunk($rows, 10);
$chunks = count($rows);
if ($chunks):
	foreach ($rows as $cid => $chunk):
		if ($cid):
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
