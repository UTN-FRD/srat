/*!
 * Formulario
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

$(function() {
	$('form:visible').each(function() {
		var self = this;

		$('div.required > .control-label, div.required > fieldset > legend', self)
		.append('&nbsp;<span class="required">*</span>');

		$('select.combobox', self).each(function() {
			var firstOption = $('option:first', this).val();
			$(this).parents('.control-group').addClass('clearfix');
			$(this).select2({
				'allowClear': (firstOption !== undefined && firstOption.length == 0)
			});
		});

		$('.checkbox-control p.help-inline', self).on('click', function() {
			var prev = $(this).prev();
			if (prev.is(':visible') && !prev.prop('disabled')) {
				prev.click();
			}
		});

		$('button.refresh', self).on('click', function() {
			$(self)
			.append('<input class="form-refresh" type="hidden" name="refresh" value="1" />')
			.submit();
		});
	});

	$('a.delete').each(function() {
		$(this)
		.removeAttr('onclick')
		.off()
		.on('click', function(e) {
			if (confirm('¿Está seguro que desea eliminar este registro?\n\n¡Esta acción no se puede deshacer!')) {
				$(this).prev().submit();
			}
			return false;
		});
	});
});
