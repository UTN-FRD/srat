<?php
/**
 * Editar
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
 * Breadcrumbs
 */
$this->Html->addCrumb('Asignaturas', array('controller' => 'asignaturas'));
$this->Html->addCrumb('Áreas', array('action' => 'index'));
$this->Html->addCrumb('Editar');
?>
<?php echo $this->Form->create('AsignaturasArea', array('class' => 'form-vertical')) ?>
<ul>
	<li>Los campos indicados con <span class="required">*</span>son obligatorios.</li>
	<?php if ($associated): ?>
		<li class="highlight">Uno o más campos no pueden modificarse debido a que este registro se encuentra asociado.</li>
	<?php endif ?>
</ul>
<fieldset>
	<?php
	echo $this->Form->hidden('id');

	echo $this->Form->input('nombre', array(
		'after' => 'Hasta 50 caracteres',
		'autofocus',
		'class' => 'span4',
		'locked' => $associated
	));

	echo $this->Form->input('obs', array(
		'after' => 'Hasta 255 caracteres',
		'class' => 'span4',
		'label' => 'Descripción',
		'type' => 'textarea'
	));
	?>
</fieldset>
<?php
echo $this->Form->buttons(array(
	'Guardar' => array('type' => 'submit'),
	'Restablecer' => array('type' => 'reset'),
	'Cancelar' => array('url' => array('action' => 'index'))
));
