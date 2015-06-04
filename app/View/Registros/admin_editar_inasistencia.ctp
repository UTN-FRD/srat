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
echo $this->Form->create('Registro');

foreach ($this->request->data['Registro'] as $index => $row):
	echo $this->Form->hidden(sprintf('Registro.%d.id', $index));
	echo $this->Form->input(
		sprintf('Registro.%d.obs', $index),
		array('label' => 'Observaciones')
	);
endforeach;

echo $this->Form->end('Enviar');
