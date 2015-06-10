<?php
/**
 * Página no encontrada
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
$this->Html->addCrumb('Página no encontrada');
?>
<p>
	Lo sentimos pero la página <?php echo (!empty($this->request->here) ? sprintf('<strong>%s</strong>', h($this->request->here)) : 'solicitado') ?> no existe.
	<br />
	Si ha seguido un vínculo hasta este lugar, es probable que el vínculo esté desactualizado o sea incorrecto.
</p>
<p>Por favor, compruebe que la dirección ingresada sea correcta e intente nuevamente.</p>
<p><a href="javascript:history.go(-1);">Volver a la página anterior</a></p>
