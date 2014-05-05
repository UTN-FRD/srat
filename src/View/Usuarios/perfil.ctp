<?php
/**
 * Perfil
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
$this->Html->css('usuarios', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Perfil');
?>
<?php echo $this->Form->create('Usuario', array('class' => 'form-vertical')) ?>
<ul>
	<li>Los campos indicados con <span class="required">*</span>son obligatorios.</li>
</ul>
<fieldset>
	<?php
	echo $this->Form->hidden('id');

	echo $this->Form->input('legajo', array(
		'autofocus',
		'class' => 'legajo',
		'label' => 'Número de legajo',
		'locked' => true,
		'max' => 16777215,
		'min' => 1,
		'required' => false
	));

	$required = (!empty($this->request->data['Usuario']['old_password']) || !empty($this->request->data['Usuario']['password']));
	echo $this->Form->input('old_password', array(
		'class' => 'span3',
		'label' => 'Contraseña anterior',
		'required' => $required,
		'type' => 'password'
	));

	echo $this->Form->input('password', array(
		'after' => 'Debe estar compuesta por letras, números y un mínimo de 6 caracteres',
		'class' => 'span3',
		'label' => 'Nueva contraseña',
		'required' => $required
	));

	echo $this->Form->input('apellido', array(
		'after' => 'Hasta 25 caracteres',
		'class' => 'span3'
	));

	echo $this->Form->input('nombre', array(
		'after' => 'Hasta 40 caracteres',
		'class' => 'span3'
	));
	?>
</fieldset>
<?php
echo $this->Form->buttons(array(
	'Guardar' => array('type' => 'submit'),
	'Restablecer' => array('type' => 'reset'),
	'Cancelar' => array('url' => array('action' => 'dashboard'))
));
