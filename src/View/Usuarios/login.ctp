<?php
/**
 * Inicio de sesión
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
echo $this->Form->create('Usuario');
echo $this->Form->input('legajo');
echo $this->Form->input('password');
echo $this->Form->end('Enviar');
