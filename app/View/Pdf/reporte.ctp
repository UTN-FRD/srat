<?php
/**
 * Reporte
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
					<span>Docente:</span>
					<?php echo h($user['docente']) ?>
				</div>
				<div>
					<span>Desde:</span>
					<?php echo date('d/m/Y', strtotime($desde)) ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div>
				</div>
				<div>
					<span>Hasta:</span>
					<?php echo date('d/m/Y', strtotime($hasta)) ?>
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
