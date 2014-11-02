/*!
 * Notificación
 *
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

$(function() {
	var _notifyHandler = function(target) {
		$(target).fadeIn('normal', function() {
			var _fadeOut = function() {
				if (target !== null) {
					$(target).fadeOut('normal', function() {
						$(target).remove();
						target = null;
					});
				}
			};

			$(target).on('click', function() {
				_fadeOut();
			}).find('button.close').on('click', function() {
				_fadeOut();
			});

			window.setTimeout(_fadeOut, 8000);
		});
	};

	$('#notifications div').each(function() {
		_notifyHandler(this);
	});
});
