/*!
 * Reportes
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

$(function() {
	var $form = $('#ReporteAdminReporteForm'),
	$table = $('#view .table');

	if ($('#ReporteAsignaturaId').val() !== '') {
		$('thead th:nth-child(2), tbody td:nth-child(2)', $table).hide();
	}

	if ($('#ReporteUsuarioId').val() !== '') {
		$('thead th:nth-child(3), tbody td:nth-child(3), thead th:nth-child(4), tbody td:nth-child(4)', $table).hide();
	}

	if ($('tr.empty', $table).length) {
		$('tbody td', $table).attr('colspan', $('thead th:visible', $table).length);
	}

	$('#ReporteAsignaturaId').on('change', function() {
		$('#ReporteUsuarioId').select2('data', null);
		$form.submit();
	});
});
