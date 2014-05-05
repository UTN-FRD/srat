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
$isAdmin = ($user['rol_id'] == 1);
$reset = (bool)$user['reset'];

/**
 * Enlaces
 */
$links = array(
	array(
		'condition' => $isAdmin,
		'text' => 'Sistema',
		'dropdown' => array(
			array(
				'text' => 'Usuarios',
				'url' => array(
					'controller' => 'usuarios',
					'action' => 'index',
					'admin' => true,
					'plugin' => false
				)
			)
		)
	),
	array(
		'condition' => $isAdmin,
		'divider' => true
	),
	array(
		'condition' => $isAdmin,
		'text' => 'Asignaturas',
		'dropdown' => array(
			array(
				'text' => 'Asignaturas',
				'url' => array(
					'controller' => 'asignaturas',
					'action' => 'index',
					'admin' => true,
					'plugin' => false
				)
			)
		)
	),
	array(
		'condition' => $isAdmin,
		'divider' => true
	),
	array(
		'condition' => !$reset,
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
				'condition' => !$reset,
				'text' => 'Perfil',
				'url' => array(
					'controller' => 'usuarios',
					'action' => 'perfil',
					'admin' => false,
					'plugin' => false
				)
			),
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
