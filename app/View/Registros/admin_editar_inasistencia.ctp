<?php
/**
 * Editar inasistencia
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
$this->Html->css('inasistencias', array('inline' => false));

/**
 * JavaScript
 */
$this->Html->script('inasistencias', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Inasistencias', array('action' => 'inasistencias'));
$this->Html->addCrumb('Editar');

/**
 * Tareas
 */
$this->set('tasks', array(
	array(
		'options' => array('class' => 'default-message'),
		'text' => 'Mensaje general'
	)
));
?>
<?php echo $this->Form->create('Registro', array('class' => 'form-vertical edit-inasistencia')) ?>
<ul>
	<li>Los campos indicados con <span class="required">*</span>son obligatorios.</li>
</ul>
<fieldset>
	<?php
	foreach ($this->request->data['Registro'] as $index => $row):
		$field = 'Registro.' . $index . '.';
		echo $this->Form->hidden($field . 'id');
		echo $this->Form->hidden($field . 'asignatura');
		echo $this->Form->hidden($field . 'usuario');
		echo $this->Form->hidden($field . 'fecha');
		?>
		<ul>
			<li><?php echo h($row['asignatura']) ?></li>
			<li><?php echo h($row['usuario']) ?></li>
			<li><?php echo date('d/m/Y', strtotime($row['fecha'])) ?></li>
		</ul>
		<?php
		echo $this->Form->input($field . 'obs', array(
			'class' => 'span6',
			'label' => 'Observaciones',
			'type' => 'text',
		));
	endforeach;
	?>
</fieldset>
<?php
echo $this->Form->buttons(array(
	'Guardar' => array('type' => 'submit'),
	'Restablecer' => array('type' => 'reset'),
	'Cancelar' => array('url' => array('action' => 'inasistencias'))
));
