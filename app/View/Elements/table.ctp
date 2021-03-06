<?php
/**
 * Tabla
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
?>
<div class="table-wrapper">
	<?php
	if (is_array($headers)):
		$colspan = count($headers);
		$headers = $this->Html->tableHeaders($headers);
	else:
		$colspan = substr_count($headers, '<th>');
	endif;

	if (!empty($rows)):
		if (is_array($rows)):
			$rows = $this->Html->tableCells($rows);
		endif;
	endif;

	if (!isset($search) || $search):
		echo $this->element('Table/search');
	endif;

	if (!empty($rows) && (!isset($pager) || $pager)):
		echo $this->element('Table/pager');
	endif;

	$classes = 'table table-bordered table-fixed table-hover table-row-numbers table-striped';
	if (!isset($tasks) || $tasks):
		$classes .= ' table-with-tasks';
	endif;

	if (!empty($class)):
		if (is_array($class)):
			$class = implode(' ', $class);
		endif;
		$classes .= " $class";
	endif;

	if (!empty($beforeTable)):
		echo $beforeTable;
	endif;
	?>
	<table class="<?php echo $classes ?>">
		<thead>
			<?php echo $headers ?>
		</thead>
		<tbody>
			<?php if (!empty($rows)): ?>
				<?php echo $rows ?>
			<?php else: ?>
				<tr class="empty">
					<?php echo $this->Html->tag('td', 'No se han encontrado registros.', compact('colspan')) ?>
				</tr>
			<?php endif ?>
		</tbody>
	</table>
	<?php
	if (!empty($afterTable)):
		echo $afterTable;
	endif;
	?>
</div>
