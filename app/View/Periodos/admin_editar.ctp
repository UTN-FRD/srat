<?php
/**
 * Editar
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
$this->Html->css('periodos', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Administrar');
$this->Html->addCrumb('Períodos no laborables', array('action' => 'index'));
$this->Html->addCrumb('Editar');
?>
<?php echo $this->Form->create('Periodo', array('class' => 'form-vertical')) ?>
<ul>
	<li>Los campos indicados con <span class="required">*</span>son obligatorios.</li>
</ul>
<fieldset>
	<?php
	echo $this->Form->hidden('id');

	$currentYear = date('Y');
	echo $this->Form->input('desde', array(
		'autofocus',
		'class' => 'datefield',
		'dateFormat' => 'DMY',
		'maxYear' => $currentYear + 1,
		'minYear' => $currentYear - 1,
		'orderYear' => 'asc'
	));

	echo $this->Form->input('hasta', array(
		'class' => 'datefield',
		'dateFormat' => 'DMY',
		'maxYear' => $currentYear + 1,
		'minYear' => $currentYear - 1,
		'orderYear' => 'asc'
	));

	echo $this->Form->input('obs', array(
		'after' => 'Hasta 255 caracteres',
		'class' => 'span5',
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
