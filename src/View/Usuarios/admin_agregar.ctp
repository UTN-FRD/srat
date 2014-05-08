<?php
/**
 * Agregar
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
 * JavaScript
 */
$this->Html->script('usuarios', array('inline' => false));

/**
 * Breadcrumbs
 */
$this->Html->addCrumb('Usuarios', array('action' => 'index'));
$this->Html->addCrumb('Agregar');
?>
<?php echo $this->Form->create('Usuario', array('class' => 'form-vertical')) ?>
<ul>
	<li>Los campos indicados con <span class="required">*</span>son obligatorios.</li>
</ul>
<fieldset>
	<?php
	echo $this->Form->input('legajo', array(
		'autofocus',
		'class' => 'legajo',
		'label' => 'Número de legajo',
		'max' => 16777215,
		'min' => 1
	));

	echo $this->Form->input('password', array(
		'after' => 'Debe estar compuesta por letras, números y un mínimo de 6 caracteres',
		'class' => 'span3',
		'label' => 'Contraseña',
		'locked' => !empty($this->request->data['Usuario']['reset']),
		'required' => empty($this->request->data['Usuario']['reset'])
	));

	echo $this->Form->input('reset', array(
		'after' => 'Solicitar al usuario que cambie la contraseña en el próximo inicio de sesión',
		'label' => 'Restablecer contraseña',
		'type' => 'checkbox'
	));

	echo $this->Form->input('rol_id', array(
		'after' => 'Tipo de usuario',
		'class' => 'span2',
		'default' => 2,
		'label' => 'Rol'
	));

	echo $this->Form->input('estado', array(
		'after' => 'Estado del usuario',
		'class' => 'span2',
		'default' => 1,
		'options' => array('Deshabilitado', 'Habilitado')
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
	'Actualizar' => array('class' => 'hidden', 'type' => 'refresh'),
	'Guardar' => array('type' => 'submit'),
	'Restablecer' => array('type' => 'reset'),
	'Cancelar' => array('url' => array('action' => 'index'))
));
