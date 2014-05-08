<?php
/**
 * Editar
 *
 * Sistema de Registro de Asistenca y Temas
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
$this->Html->css('cargos', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Asignaturas', array('controller' => 'asignaturas'));
$this->Html->addCrumb('Usuarios', array('action' => 'index'));
$this->Html->addCrumb('Editar');
?>
<?php echo $this->Form->create('Cargo', array('class' => 'form-vertical')) ?>
<ul>
	<li>Los campos indicados con <span class="required">*</span>son obligatorios.</li>
	<?php if ($associated): ?>
		<li class="highlight">Uno o más campos no pueden modificarse debido a que este registro se encuentra asociado.</li>
	<?php endif ?>
</ul>
<fieldset>
	<?php
	echo $this->Form->hidden('id');

	echo $this->Form->input('asignatura_id', array(
		'class' => 'combobox span8',
		'locked' => $associated
	));

	echo $this->Form->input('usuario_id', array(
		'class' => 'combobox span5',
		'locked' => $associated
	));

	echo $this->Form->input('grado_id', array(
		'class' => 'span3'
	));

	echo $this->Form->input('tipo_id', array(
		'class' => 'span2',
		'label' => 'Tipo de cargo'
	));

	echo $this->Form->input('dedicacion', array(
		'default' => 1,
		'label' => 'Dedicación',
		'maxlength' => 3,
		'type' => 'text'
	));

	echo $this->Form->input('dedicacion_id', array(
		'class' => 'span2',
		'label' => 'Tipo de dedicación'
	));

	echo $this->Form->input('resolucion', array(
		'label' => 'Resolución',
		'max' => 65535,
		'min' => 1
	));
	?>
</fieldset>
<?php
echo $this->Form->buttons(array(
	'Guardar' => array('type' => 'submit'),
	'Restablecer' => array('type' => 'reset'),
	'Cancelar' => array('url' => array('action' => 'index'))
));
