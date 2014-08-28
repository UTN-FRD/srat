<?php
/**
 * Dashboard
 *
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
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
	echo $this->Form->create('Registro');

	foreach ($this->request->data['Registro'] as $rid => $row):
		if (!empty($row['id'])):
			echo $this->Form->hidden(sprintf('Registro.%d.id', $rid));
		endif;

		echo $this->Form->hidden(sprintf('Registro.%d.fecha', $rid));
		echo $this->Form->hidden(sprintf('Registro.%d.cargo_id', $rid));
		echo $this->Form->hidden(sprintf('Tipo.%d.nombre', $rid));
		echo $this->Form->hidden(sprintf('Cargo.%d.asignatura', $rid));
		echo $this->Form->hidden(sprintf('Grado.%d.nombre', $rid));
		?>
		<table class="asignaturas table table-bordered">
			<tr>
				<th>Asignatura</th>
				<th>Cargo</th>
			</tr>
			<tr>
				<td><?php echo h($this->request->data['Cargo'][$rid]['asignatura']) ?></td>
				<td><?php printf('(%s) %s', h($this->request->data['Tipo'][$rid]['nombre']), h($this->request->data['Grado'][$rid]['nombre'])) ?></td>
			</tr>
			<tr>
				<th>Hora de ingreso</th>
				<th>Hora de salida</th>
			</tr>
			<tr>
				<td>
					<?php
					echo $this->Form->input(sprintf('Registro.%d.entrada', $rid), array(
						'class' => 'span1',
						'div' => array('class' => 'time'),
						'empty' => true,
						'interval' => 5,
						'label' => false,
						'required' => false,
						'separator' => ' : ',
						'timeFormat' => 24
					));
					?>
				</td>
				<td>
					<?php
					echo $this->Form->input(sprintf('Registro.%d.salida', $rid), array(
						'class' => 'span1',
						'div' => array('class' => 'time'),
						'empty' => true,
						'interval' => 5,
						'label' => false,
						'required' => false,
						'separator' => ' : ',
						'timeFormat' => 24
					));
					?>
				</td>
			</tr>
			<tr>
				<th colspan="2">Temas del día</th>
			</tr>
			<tr>
				<td colspan="2">
					<?php
					echo $this->Form->input(sprintf('Registro.%d.obs', $rid), array(
						'label' => false,
						'required' => false
					));
					?>
				</td>
			</tr>
		</table>
		<?php
	endforeach;

	echo $this->Form->buttons(array('Guardar cambios' => array('type' => 'submit')), false);
	echo $this->Form->end();
	?>
<?php endif ?>
