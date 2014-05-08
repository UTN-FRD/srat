<?php
/**
 * Buscador de registros
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
?>
<div class="clearfix table-search">
	<?php
	echo $this->Form->create(null, array(
		'class' => 'form-inline form-search pull-right',
		'url' => $this->request->params['pass']
	));

	echo $this->Form->text('buscar', array(
		'autofocus',
		'class' => 'search-query',
		'maxlength' => 50,
		'placeholder' => 'Buscar...'
	));

	echo $this->Form->end();
	?>
</div>
