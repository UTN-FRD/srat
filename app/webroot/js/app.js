/*!
 * Aplicación
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
	if (!$('#UsuarioLoginForm, .admin-users').length) {
		var idleCounter = 0,
		idleInterval = window.setInterval(function() {
			if (++idleCounter >= 120) {
				window.clearInterval(idleInterval);
				window.location = $('a.logout').attr('href');
			}
		}, 1000);
	}

	$(document).on('keypress mousedown mousemove scroll wheel', function() {
		idleCounter = 0;
	});
});
