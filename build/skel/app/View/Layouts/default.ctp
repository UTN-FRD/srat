<?php
/**
 * Diseño predeterminado
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
?>
<!DOCTYPE html>
<html dir="ltr" lang="es">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />

		<title><?php echo h($title_for_layout) ?> :: Sistema de Registro de Asistencia y Temas</title>

		<meta name="application-name" content="Sistema de Registro de Asistencia y Temas" />
		<meta name="author" content="Facultad Regional Delta" />

		<?php
		echo $this->fetch('meta');
		echo $this->Html->meta('icon');

		echo $this->Html->css(array('bootstrap.min', 'style'));
		echo $this->Html->script('script');

		echo $this->fetch('css');
		echo $this->fetch('script');
		?>
	</head>
	<body>
		<div id="wrapper">
			<div id="notifications">
				<?php
				echo $this->Session->flash('auth');
				echo $this->Session->flash();
				?>
			</div>

			<?php echo $this->element('navbar') ?>

			<div id="page">
				<div class="clearfix crumbs">
					<?php
					echo $this->Html->getCrumbList(
						array('class' => 'breadcrumb', 'lastClass' => 'active', 'separator' => '<span class="divider">&gt;</span>'),
						'Inicio'
					);
					?>

					<div class="date">
						<?php
						echo preg_replace_callback(
							"/[a-zA-Záéíóú]{3,}/u",
							function($m) {
								return ucfirst($m[0]);
							},
							CakeTime::format(time(), '%A %d de %B de %Y, %H:%M')
						);
						?>
					</div>
				</div>

				<div id="content">
					<?php
					if (isset($tasks)):
						echo $this->Html->generateLinkList($tasks, array('class' => 'nav nav-pills page-tasks'));
					endif
					?>

					<h3 class="page-header">
						<?php echo (!empty($title_for_view) ? h($title_for_view) : h($title_for_layout)) ?>
					</h3>

					<div id="view" class="clearfix">
						<?php echo $this->fetch('content') ?>
					</div>
				</div>

				<div id="footer">
					Desarrollado por <a href="http://www.frd.utn.edu.ar/" target="_blank">Facultad Regional Delta</a>.
				</div>
			</div>
		</div>
		<noscript class="noscript">
			La experiencia con esta aplicación puede verse afectada debido a que JavaScript está deshabilitado.
		</noscript>
	</body>
</html>
