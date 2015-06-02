<?php
/**
 * Tabla para reportes de asistencia
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
			<?php if (empty($data['asignatura'])): ?>
				<th class="row1">Asignatura</th>
			<?php endif ?>

			<?php if (empty($data['usuario'])): ?>
				<th class="row2">Legajo</th>
				<th class="row3">Usuario</th>
			<?php endif ?>

			<th class="row4">Fecha</th>
			<th class="row5">Entrada</th>
			<th class="row6">Salida</th>
			<th class="row7">Temas</th>
		</tr>
	</thead>
	<tbody>
	<?php
		if (!empty($chunk)):
			foreach ($chunk as $rid => $row):
				$asistencia = ($row['Registro']['tipo'] === '1');
		?>
		<tr>
			<?php if (empty($data['asignatura'])): ?>
				<td class="row1"><?php echo str_replace(':', ':<br />', h($row['Registro']['asignatura'])) ?></td>
			<?php endif ?>

			<?php if (empty($data['usuario'])): ?>
				<td class="row2"><?php echo $row['Usuario']['legajo'] ?></td>
				<td class="row3"><?php echo h(sprintf('%s, %s', $row['Usuario']['apellido'], $row['Usuario']['nombre'])) ?></td>
			<?php endif ?>

			<td class="row4">
				<?php echo date('d/m/Y' . ($asistencia ? ' H:i:s' : ''), strtotime($row['Registro']['fecha'])) ?>
			</td>

			<td class="row5">
				<?php echo ($asistencia ? date('H:i', strtotime($row['Registro']['entrada'])) : '-') ?>
			</td>

			<td class="row6">
				<?php echo ($asistencia ? date('H:i', strtotime($row['Registro']['salida'])) : '-') ?>
			</td>

			<td class="row7">
				<?php echo ($asistencia ? nl2br(h($row['Registro']['obs'])) : '-') ?>
			</td>
		</tr>
		<?php endforeach ?>
		<?php else: ?>
			<?php
				$colspan = 7;
				if (!empty($data['asignatura'])):
					$colspan--;
				endif;

				if (!empty($data['usuario'])):
					$colspan -= 2;
				endif;
			?>
			<tr class="empty">
				<td colspan="<?php echo $colspan ?>">No se han encontrado registros.</td>
			</tr>
		<?php endif ?>
	</tbody>
</table>
