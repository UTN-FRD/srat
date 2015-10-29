<?php
/**
 * Barra de navegación
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
 * Usuario
 */
$isAdmin = $loggedIn = $reset = false;
$user = AuthComponent::user();
if ($user):
	$isAdmin = (bool)$user['admin'];
	$loggedIn = true;
	$reset = (bool)$user['reset'];
endif;

/**
 * Enlaces
 */
$links = array(
	array(
		'condition' => $isAdmin,
		'text' => 'Sistema',
		'dropdown' => array(
			array(
				'options' => array('class' => 'admin-users'),
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
		'text' => 'Administrar',
		'dropdown' => array(
			array(
				'text' => 'Áreas',
				'url' => array(
					'controller' => 'asignaturas_areas',
					'action' => 'index',
					'admin' => true,
					'plugin' => false
				)
			),
			array(
				'text' => 'Asignaturas',
				'url' => array(
					'controller' => 'asignaturas',
					'action' => 'index',
					'admin' => true,
					'plugin' => false
				)
			),
			array(
				'text' => 'Cargos',
				'url' => array(
					'controller' => 'cargos',
					'action' => 'index',
					'admin' => true,
					'plugin' => false
				)
			),
			array(
				'text' => 'Carreras',
				'url' => array(
					'controller' => 'asignaturas_carreras',
					'action' => 'index',
					'admin' => true,
					'plugin' => false
				)
			),
			array(
				'text' => 'Horarios',
				'url' => array(
					'controller' => 'horarios',
					'action' => 'index',
					'admin' => true,
					'plugin' => false
				)
			),
			array(
				'text' => 'Materias',
				'url' => array(
					'controller' => 'asignaturas_materias',
					'action' => 'index',
					'admin' => true,
					'plugin' => false
				)
			),
			array(
				'text' => 'Períodos no laborables',
				'url' => array(
					'controller' => 'periodos',
					'action' => 'index',
					'admin' => true,
					'plugin' => false
				)
			)
		)
	),
	array(
		'condition' => $isAdmin,
		'options' => array('escape' => false),
		'text' => 'Inasistencias <span class="badge badge-info">' . (isset($totalAbsences) ? $totalAbsences : 0) . '</span>',
		'url' => array(
			'controller' => 'inasistencias',
			'action' => 'index',
			'admin' => true,
			'plugin' => false
		)
	),
	array(
		'condition' => $isAdmin,
		'text' => 'Reportes',
		'dropdown' => array(
			array(
				'text' => 'Generar reporte',
				'url' => array(
					'controller' => 'registros',
					'action' => 'generar_reporte',
					'admin' => true,
					'plugin' => false
				)
			),
			array(
				'divider' => true
			),
			array(
				'text' => 'Asistencia general',
				'url' => array(
					'controller' => 'registros',
					'action' => 'asistencia_general',
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
		'options' => array('class' => 'strong'),
		'text' => (isset($user['nombre_completo']) ? $user['nombre_completo'] : null),
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
			)
		)
	),
	array(
		'condition' => $loggedIn,
		'options' => array('class' => 'logout'),
		'text' => 'Cerrar sesión',
		'url' => array(
			'controller' => 'usuarios',
			'action' => 'logout',
			'admin' => false,
			'plugin' => false
		)
	)
);
?>
<div class="navbar">
	<div class="navbar-inner">
		<?php
		echo $this->Html->link('Sistema de Registro de Asistencia y Temas', '/', array('class' => 'brand'));
		echo $this->Html->generateLinkList($links, array('class' => 'nav pull-right'));
		?>
	</div>
</div>
