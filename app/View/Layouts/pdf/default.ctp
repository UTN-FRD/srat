<?php
/**
 * PDF
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
<!DOCTYPE html>
<html dir="ltr" lang="es">
	<head>
		<meta charset="utf-8" />

		<title><?php echo h($title_for_layout) ?> :: Sistema de Registro de Asistencia y Temas</title>

		<meta name="application-name" content="Sistema de Registro de Asistencia y Temas" />
		<meta name="author" content="Facultad Regional Delta" />

		<?php
		echo $this->fetch('css');
		echo $this->fetch('script');
		echo $this->Html->css('layout.pdf', array('fullBase' => true));
		?>
	</head>
	<body>
		<div id="wrapper">
			<?php echo $this->fetch('content') ?>
		</div>
	</body>
</html>
