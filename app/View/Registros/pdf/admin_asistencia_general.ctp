<?php
/**
 * Reporte de asistencia y/o inasistencia
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
<table class="details">
	<tbody>
		<tr>
			<td>
				<div>
					<span>Carrera:</span>
					<?php echo h($carreras[$data['carrera_id']]) ?>
				</div>
				<div>
					<span>Desde:</span>
					<?php echo date('d/m/Y', strtotime($data['desde'])) ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div>
				</div>
				<div>
					<span>Hasta:</span>
					<?php if (!empty($data['hasta'])): ?>
						<?php echo date('d/m/Y', strtotime($data['hasta'])) ?>
					<?php else: ?>
						<?php echo date('d/m/Y', time()) ?>
					<?php endif ?>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<?php
$records = array();
foreach($rows as $row):
	$records[$row['Materia']['nombre']][$row['Registro']['usuario']] = $row[0];
endforeach;
unset($rows, $row);
ksort($records);
foreach ($records as &$record):
	ksort($record);
endforeach;

$pushRow = function ($row) use (&$rows) {
	if (empty($rows)):
		$rows[] = array();
	endif;
	$index = count($rows) - 1;
	if (count($rows[$index]) == 20):
		$rows[] = array();
		$index++;
	endif;
	$rows[$index][] = $row;
};

$rows = array();
foreach ($records as $materia => $docentes):
	foreach ($docentes as $docente => $values):
		$pushRow(array_merge(
			array(h($materia), h($docente)),
			$values
		));
	endforeach;
endforeach;

$count = count($rows);
foreach ($rows as $rid => $chunk):
	if ($rid):
		echo '<br />';
	endif;

	echo $this->element(
		'Report/table_general',
		compact('chunk')
	);

	if ($rid < ($count - 1)):
		echo '<div class="page-break"></div>';
	endif;
endforeach;
