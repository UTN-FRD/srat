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
	var $table = $('.report-preview');
	if ($('.report-builder').length) {
		$('#ReporteAsignaturaId').on('change', function() {
			$('#ReporteUsuarioId').select2('data', null);
			$('#ReporteAdminGenerarReporteForm').submit();
		});

		if ($('#ReporteAsignaturaId').val() !== '') {
			$('thead th:nth-child(2), tbody td:nth-child(2)', $table).hide();
		}

		if ($('#ReporteUsuarioId').val() !== '') {
			$('thead th:nth-child(3), tbody td:nth-child(3), thead th:nth-child(4), tbody td:nth-child(4)', $table).hide();
		}

		if ($('tr.empty', $table).length) {
			$('tbody td', $table).attr('colspan', $('thead th:visible', $table).length);
		}
	}

	if ($('.report-general').length) {
		$table.removeClass('table-row-numbers');

		$('th:nth-child(1)', $table)
		.data('sort', 'string')
		.addClass('sorting-asc')
		.prepend('<span class="arrow">&uarr;</span>');
		$('th:nth-child(2)', $table).data('sort', 'string');
		$('th:nth-child(3)', $table).data('sort', 'int');
		$('th:nth-child(4)', $table).data('sort', 'int');
		$('th:nth-child(5)', $table).data('sort', 'int');

		var tObject = $table.stupidtable();
		tObject.on('aftertablesort', function (event, data) {
	        var th = $(this).find("th"),
	        dir = $.fn.stupidtable.dir,
	        arrow = data.direction === dir.ASC ? '&uarr;' : '&darr;';
	        th.find('.arrow').remove();
	        th.eq(data.column).prepend('<span class="arrow">' + arrow +'</span>');
		});

		$('#ReporteCarreraId:enabled').on('change', function() {
			$('#ReporteAdminAsistenciaGeneralForm').submit();
		});
	}
});
