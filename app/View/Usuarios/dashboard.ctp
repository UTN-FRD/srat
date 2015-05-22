<?php
/**
 * Dashboard
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
$this->Html->css('dashboard', array('inline' => false));

/**
 * JavaScript
 */
$this->Html->script('dashboard', array('inline' => false));
?>
<?php if (empty($this->request->data['Registro'])): ?>
	<div class="alert alert-info">
		No hay asignaturas asociadas a su usuario en el día de la fecha.
	</div>
<?php else: ?>
	<?php
	echo $this->Form->create('Registro', array('class' => 'form-asignaturas'));
	foreach ($this->request->data['Registro'] as $rid => $row):
		$class = ($rid % 2 === 0 ? 'left' : 'right');
		if (!empty($row['id'])):
			echo $this->Form->hidden(sprintf('Registro.%d.id', $rid));
		endif;
		echo $this->Form->hidden(sprintf('Cargo.%d.asignatura', $rid));
		echo $this->Form->hidden(sprintf('Registro.%d.asignatura_id', $rid));
		echo $this->Form->hidden(sprintf('Registro.%d.fecha', $rid));
		echo $this->Form->hidden(sprintf('Registro.%d.tipo', $rid));
		echo $this->Form->hidden(sprintf('Registro.%d.usuario_id', $rid));
		?>
		<table class="table table-bordered table-condensed table-<?php echo $class ?>">
			<thead>
				<tr>
					<th>Asignatura</th>
					<th>Entrada</th>
					<th>Salida</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo h($this->request->data['Cargo'][$rid]['asignatura']) ?></td>
					<td>
						<?php
						echo $this->Form->input(sprintf('Registro.%d.entrada', $rid), array(
							'error' => false, 'interval' => 5, 'label' => false,
							'required' => false, 'separator' => ' : ', 'timeFormat' => 24
						))
						?>
					</td>
					<td>
						<?php
						echo $this->Form->input(sprintf('Registro.%d.salida', $rid), array(
							'error' => false, 'interval' => 5, 'label' => false,
							'required' => false, 'separator' => ' : ', 'timeFormat' => 24
						))
						?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<?php
						echo $this->Form->input(sprintf('Registro.%d.obs', $rid), array(
							'error' => false, 'label' => false, 'required' => false, 'rows' => 2
						))
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	endforeach;
	?>
	<div class="clearfix">
	</div>
	<?php echo $this->Form->buttons(array('Guardar cambios' => array('type' => 'submit'))) ?>
<?php endif ?>
