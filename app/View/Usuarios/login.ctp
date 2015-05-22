<?php
/**
 * Inicio de sesión
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
$this->Html->css('login', array('inline' => false));

echo $this->Form->create('Usuario');
echo $this->Form->input('legajo', array(
	'autofocus',
	'label' => 'Número de legajo',
	'min' => 1
));
echo $this->Form->input('password', array(
	'label' => 'Contraseña'
));
echo $this->Form->button('Iniciar sesión', array(
	'class' => 'btn',
	'type' => 'submit'
));
echo $this->Form->end();
