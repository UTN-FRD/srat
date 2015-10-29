<?php
/**
 * Tabla para reportes de asistencia general
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
<table class="table table-bordered table-condensed report-table">
	<thead>
		<tr>
			<th class="row1">Materia</th>
			<th class="row2">Docente</th>
			<th class="row3">Asistencias</th>
			<th class="row4">Inasistencias</th>
			<th class="row5">Días sin actividad</th>
		</tr>
	</thead>
	<tbody>
	<?php
	foreach ($chunk as $row):
		?>
		<tr>
			<td class="row1"><?php echo $row[0] ?></td>
			<td class="row2"><?php echo $row[1] ?></td>
			<td class="row3"><?php echo $row['asistencia'] ?></td>
			<td class="row4"><?php echo $row['inasistencia'] ?></td>
			<td class="row5"><?php echo $row['sin_actividad'] ?></td>
		</tr>
		<?php
	endforeach;
	?>
	</tbody>
</table>
