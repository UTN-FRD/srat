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
$this->Html->css(
	array('bootstrap.min', 'reportes'),
	array('fullBase' => true, 'inline' => false)
);
?>
<table class="table table-condensed table-details">
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

<table class="table table-bordered table-condensed">
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
		if (!empty($rows)):
			foreach ($rows as $rid => $row):
				$asistencia = ($row['Registro']['tipo'] == '1');
		?>
		<tr>
			<?php if (empty($data['asignatura'])): ?>
				<td class="row1"><?php echo h($row['Registro']['asignatura']) ?></td>
			<?php endif ?>

			<?php if (empty($data['usuario'])): ?>
				<td class="row2"><?php echo $row['Usuario']['legajo'] ?></td>
				<td class="row3"><?php echo h(sprintf('%s, %s', $row['Usuario']['apellido'], $row['Usuario']['nombre'])) ?></td>
			<?php endif ?>

			<td class="row4"><?php echo date('d/m/Y', strtotime($row['Registro']['fecha'])) ?></td>
			<td class="row5"><?php echo ($asistencia ? date('H:i', strtotime($row['Registro']['entrada'])) : '-') ?></td>
			<td class="row6"><?php echo ($asistencia ? date('H:i', strtotime($row['Registro']['salida'])) : '-') ?></td>
			<td class="row7"><?php echo ($asistencia ? nl2br(h($row['Registro']['obs'])) : '-') ?></td>
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
