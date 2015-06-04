/*!
 * Inasistencias
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
	var editLink = $('.page-tasks .edit-link'),
	table = $('.table-wrapper > form > table');

	if (editLink.length) {
		editLink.data('href', editLink.attr('href'))
		.attr('href', '#')
		.on('click', function() {
			if (!$(this).parent().hasClass('disabled')) {
				$('.form-inasistencia').submit();
			}
		})
		.parent().addClass('disabled');

		checkedFn = function(e) {
			var checkCount = $('tbody input:checked', table).length;

			if (e.checked) {
				$(e).parents('tr').addClass('checked');
			} else {
				$(e).parents('tr').removeClass('checked');
			}

			if (checkCount) {
				editLink.parent().removeClass('disabled');
			} else {
				editLink.parent().addClass('disabled');
			}

			$('.check-counter').html(checkCount);
		};

		$('tbody > tr > td > input:checked', table).each(function() {
			checkedFn(this);
		});

		$('thead > tr > th:nth-child(2) > input', table).on('click', function() {
			$('tbody > tr > td > input', table).prop('checked', this.checked)
			.trigger('change');
		});

		$('tbody > tr > td > input', table).checkboxRangeSelection();
		$('tbody > tr > td > input', table).on('change', function() {
			checkedFn(this);
		});
	}
});

/**
 * checkboxRangeSelection()
 * http://www.barneyb.com/barneyblog/2008/01/08/checkbox-range-selection-a-la-gmail/
 */
jQuery.fn.checkboxRangeSelection = function() {
    var lastCheckbox = null,
    $target = this;

    $target.off('click.rangeselection')
    .on('click.rangeselection', function(e) {
        if (lastCheckbox !== null && (e.shiftKey || e.metaKey)) {
            $target.slice(
                Math.min($target.index(lastCheckbox), $target.index(e.target)),
                Math.max($target.index(lastCheckbox), $target.index(e.target)) + 1
            )
            .prop('checked', e.target.checked)
            .trigger('change');
        }
        lastCheckbox = e.target;
    });
};
