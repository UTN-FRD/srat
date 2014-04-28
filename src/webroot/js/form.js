/*!
 * Formulario
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

$(function() {
	$('form:visible').each(function() {
		var self = this;

		$('div.required > .control-label, div.required > fieldset > legend', self)
		.append('&nbsp;<span class="required">*</span>');

		$('button.refresh', self).on('click', function() {
			$(self)
			.append('<input class="form-refresh" type="hidden" name="refresh" value="1" />')
			.submit();
		});
	});
});
