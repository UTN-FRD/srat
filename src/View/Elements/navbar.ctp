<?php
/**
 * Barra de navegación
 *
 * Sistema de Registro de Asistenca y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */

/**
 * Usuario
 */
$user = AuthComponent::user();
$loggedIn = (bool)$user;

/**
 * Enlaces
 */
$links = array(
	array(
		'text' => 'Docentes',
		'url' => array(
			'controller' => 'usuarios',
			'action' => 'docentes',
			'admin' => false,
			'plugin' => false
		)
	),
	array(
		'condition' => !$loggedIn,
		'text' => 'Inicio de sesión',
		'url' => array(
			'controller' => 'usuarios',
			'action' => 'login',
			'admin' => false,
			'plugin' => false
		)
	),
	array(
		'condition' => $loggedIn,
		'text' => $user['nombre_completo'],
		'dropdown' => array(
			array(
				'text' => 'Cerrar sesión',
				'url' => array(
					'controller' => 'usuarios',
					'action' => 'logout',
					'admin' => false,
					'plugin' => false
				)
			)
		)
	)
);
?>
<div class="navbar">
	<div class="navbar-inner">
		<?php
		echo $this->Html->link('Sistema de Registro de Asistenca y Temas', '/', array('class' => 'brand'));
		echo $this->Html->generateLinkList($links, array('class' => 'nav pull-right'));
		?>
	</div>
</div>
