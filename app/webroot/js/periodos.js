/*!
 * Períodos
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
	$('.upload-link > a').on('click', function(e) {
		e.preventDefault();
		$('#PeriodoArchivo').trigger('click');
	});

	$('#PeriodoArchivo').on('change', function() {
		$('#PeriodoImportarForm').submit();
	});
});
