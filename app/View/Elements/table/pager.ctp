<?php
/**
 * Paginador de registros
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

/**
 * Parámetros
 */
$params = $this->Paginator->params();
?>
<div class="clearfix table-pager">
	<div class="pager-counter">
		<?php echo $this->Paginator->counter('Mostrando registros {:start} - {:end} de {:count}') ?>
	</div>

	<div class="pager-numbers">
		<?php if ($params['pageCount'] == 1): ?>
			Página 1 de 1
		<?php else: ?>
			<span class="pager-controls">
				<?php
				if ($params['page'] > 1):
					echo $this->Paginator->first('«« Primera');
					echo $this->Paginator->prev('« Anterior');
				endif;

				echo $this->Paginator->numbers(array('separator' => '&nbsp;'));

				if ($params['page'] < $params['pageCount']):
					echo $this->Paginator->next('Siguiente »');
					echo $this->Paginator->last('Última »»');
				endif;
				?>
			</span>
		<?php endif ?>
	</div>
</div>
