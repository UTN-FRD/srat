<?php
/**
 * Notificación
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
?>
<div class="notify-<?php echo (isset($level) ? $level : 'error') ?>">
	<button class="close">&times;</button>
	<?php echo h($message) ?>
</div>
