<?php
/**
 * Página no disponible
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
 * Breadcrumbs
 */
$this->Html->addCrumb('Página no disponible');
?>
<p>
	Lo sentimos pero la página <?php echo (!empty($this->request->here) ? sprintf('<strong>%s</strong>', h($this->request->here)) : 'solicitado') ?> no está disponible debido a un error interno.
	<br />
	Por favor, aguarde unos segundos y vuelva a intentarlo.
</p>
<p>Si el problema persiste, consulte con el Administrador.</p>
<p><a href="javascript:history.go(-1);">Volver a la página anterior</a></p>
