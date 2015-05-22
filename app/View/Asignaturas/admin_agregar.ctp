<?php
/**
 * Agregar
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
 * Breadcrumbs
 */
$this->Html->addCrumb('Asignaturas', array('controller' => 'asignaturas', 'action' => 'index'));
$this->Html->addCrumb('Agregar');
?>
<?php echo $this->Form->create('Asignatura', array('class' => 'form-vertical')) ?>
<ul>
	<li>Los campos indicados con <span class="required">*</span>son obligatorios.</li>
</ul>
<fieldset>
	<?php
	echo $this->Form->input('carrera_id', array(
		'class' => 'combobox span5',
		'label' => 'Carrera'
	));

	echo $this->Form->input('materia_id', array(
		'class' => 'combobox span5',
		'label' => 'Materia'
	));

	echo $this->Form->input('area_id', array(
		'class' => 'combobox span5',
		'label' => 'Área'
	));

	echo $this->Form->input('nivel_id', array(
		'class' => 'span2',
		'label' => 'Nivel'
	));

	echo $this->Form->input('tipo_id', array(
		'class' => 'span2',
		'label' => 'Tipo'
	));
	?>
</fieldset>
<?php
echo $this->Form->buttons(array(
	'Guardar' => array('type' => 'submit'),
	'Restablecer' => array('type' => 'reset'),
	'Cancelar' => array('url' => array('action' => 'index'))
));
