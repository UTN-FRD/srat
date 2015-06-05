/*!
 * Dashboard
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
	if ($('.form-asignaturas .table').length < 2) {
		$('body').addClass('page-small');
	}

	$('.form-asignaturas td:nth-child(2) select:first-child').each(function() {
		$('option', this).slice(0, 8).remove();
	});
});
