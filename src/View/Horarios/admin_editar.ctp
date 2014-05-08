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
 * CSS
 */
$this->Html->css('horarios', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Asignaturas', array('controller' => 'asignaturas'));
$this->Html->addCrumb('Horarios', array('action' => 'index'));
$this->Html->addCrumb('Editar');
?>
<?php echo $this->Form->create('Horario', array('class' => 'form-vertical')) ?>
<ul>
	<li>Los campos indicados con <span class="required">*</span>son obligatorios.</li>
</ul>
<fieldset>
	<?php
	echo $this->Form->hidden('id');

	echo $this->Form->input('asignatura_id', array(
		'class' => 'combobox span8'
	));

	echo $this->Form->input('dia', array(
		'class' => 'span2',
		'default' => date('w'),
		'label' => 'Día',
		'options' => array('Domingo', 'Lunes', 'Martes', 'Míéroles', 'Jueves', 'Viernes', 'Sábado')
	));

	echo $this->Form->input('entrada', array(
		'class' => 'span1',
		'div' => array(
			'class' => 'time'
		),
		'interval' => 5,
		'timeFormat' => 24
	));

	echo $this->Form->input('salida', array(
		'class' => 'span1',
		'div' => array(
			'class' => 'time'
		),
		'interval' => 5,
		'timeFormat' => 24
	));
	?>
</fieldset>
<?php
echo $this->Form->buttons(array(
	'Guardar' => array('type' => 'submit'),
	'Restablecer' => array('type' => 'reset'),
	'Cancelar' => array('url' => array('action' => 'index'))
));
