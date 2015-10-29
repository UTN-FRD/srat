<?php
/**
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
 * Dependencias
 */
App::uses('AppController', 'Controller');

/**
 * Registros
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class RegistrosController extends AppController {

/**
 * Componentes
 *
 * @var array
 */
	public $components = array(
		'Paginator',
		'RequestHandler'
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->loadModel('Reporte');
	}

/**
 * Responde a solicitudes invalidadas por el componente Security
 *
 * @param null|string $type Tipo de error
 *
 * @return void
 */
	public function blackHole($type = null) {
		$this->Session->delete($this->_getSessionKey());

		parent::blackHole($type);
	}

/**
 * Índice
 *
 * @return void
 */
	public function admin_index() {
		$this->redirect(array('action' => 'generar_reporte'));
	}

/**
 * Genera reportes
 *
 * @return void
 */
	public function admin_generar_reporte() {
		if (!empty($this->request->named['reset'])) {
			$this->Session->delete($this->_getSessionKey());
			$this->redirect(array('action' => 'generar_reporte'));
		}

		$options = $this->Session->read($this->_getSessionKey('Options'));
		if (!$options) {
			$options = array(
				'data' => array(),
				'paging' => array()
			);
		}

		if ($this->request->is('post')) {
			$this->Reporte->create($this->request->data);
			$fieldList = array('asignatura_id', 'usuario_id', 'desde', 'hasta', 'tipo');
			if ($this->Reporte->validates(compact('fieldList'))) {
				$options['data'] = $this->Reporte->data['Reporte'];
			} else {
				$options['data'] = array();
			}
		}

		if (!$this->request->data) {
			if ($options['data']) {
				$this->request->data['Reporte'] = $options['data'];
			} else {
				$this->request->data = array(
					'Reporte' => array(
						'asignatura_id' => null,
						'usuario_id' => null,
						'desde' => null,
						'hasta' => null,
						'tipo' => 1
					)
				);
				$options['data'] = $this->request->data['Reporte'];
			}
		}

		$findOptions = array(
			'fields' => array(
				'asignatura', 'Usuario.legajo', 'usuario', 'tipo', 'fecha', 'entrada', 'salida', 'obs'
			),
			'order' => array('Registro.fecha' => 'desc'),
			'recursive' => 0
		);
		if (!empty($options['data']['asignatura_id'])) {
			$findOptions['conditions']['Registro.asignatura_id'] = $options['data']['asignatura_id'];
		}
		if (!empty($options['data']['usuario_id'])) {
			$findOptions['conditions']['Registro.usuario_id'] = $options['data']['usuario_id'];
		}
		if (!empty($options['data']['desde'])) {
			$findOptions['conditions']['CAST(Registro.fecha as DATE) >='] = $options['data']['desde'];
		}
		if (!empty($options['data']['hasta'])) {
			$findOptions['conditions']['CAST(Registro.fecha as DATE) <='] = $options['data']['hasta'];
		}
		if (isset($options['data']['tipo'])) {
			$options['data']['tipo'] = (int)$options['data']['tipo'];
			if ($options['data']['tipo'] == 0) {
				$findOptions['conditions']['Registro.tipo'] = 0;
			} elseif ($options['data']['tipo'] == 1) {
				$findOptions['conditions']['Registro.tipo'] = 1;
			} else {
				$findOptions['conditions']['Registro.tipo'] = array(0, 1);
			}
		}
		if (!empty($options['paging']['order'])) {
			$findOptions['order'] = $options['paging']['order'];
		}

		if ($this->request->ext === 'pdf') {
			$title = 'Asistencia';
			if (isset($options['data']['tipo'])) {
				if ($options['data']['tipo'] === '0') {
					$title = 'Inasistencia';
				} elseif ($options['data']['tipo'] === '2') {
					$title .= ' e Inasistencia';
				}
			}
			$this->_setupCakePdf('Reporte de ' . $title);

			$data = $options['data'];
			if (!empty($data['asignatura_id'])) {
				$this->Registro->Asignatura->recursive = 0;
				$this->Registro->Asignatura->id = $data['asignatura_id'];
				$data['asignatura'] = $this->Registro->Asignatura->field('asignatura');
			}
			if (!empty($data['usuario_id'])) {
				$this->Registro->Usuario->id = $data['usuario_id'];
				$data['usuario'] = $this->Registro->Usuario->field('docente');
			}

			$this->set(array(
				'data' => $data,
				'rows' => $this->Registro->find('all', $findOptions)
			));

			try {
				$this->render();
			} catch (Exception $e) {
				$this->_notify(null, array(
					'message' => 'No fue posible exportar el resultado debido a un error interno.',
					'redirect' => array('action' => 'generar_reporte')
				));
			}
		} else {
			$this->Paginator->settings = array_merge(
				$this->Paginator->settings,
				$findOptions,
				array('limit' => 10, 'maxLimit' => 10)
			);

			$findCondition = array();
			if (!empty($options['data']['asignatura_id'])) {
				$findCondition = array('asignatura_id' => $options['data']['asignatura_id']);
			}
			$this->set(array(
				'asignaturas' => $this->Registro->getAsignaturasList(),
				'usuarios' => $this->Registro->getUsuariosList($findCondition),
				'rows' => $this->Paginator->paginate(),
				'title_for_layout' => 'Generar reporte - Reportes',
				'title_for_view' => 'Generar reporte'
			));

			if (isset($this->params['paging']['Registro']['order'])) {
				$options['paging']['order'] = $this->params['paging']['Registro']['order'];
			}

			$this->Session->write($this->_getSessionKey('Options'), $options);
		}
	}

/**
 * Genera un reporte de asistencia general
 *
 * @return void
 */
	public function admin_asistencia_general() {
		if (!empty($this->request->named['reset'])) {
			$this->Session->delete($this->_getSessionKey());
			$this->redirect(array('action' => 'asistencia_general'));
		}

		$options = $this->Session->read($this->_getSessionKey('Options'));
		if (!$options) {
			$options = array(
				'data' => array()
			);
		}

		if ($this->request->is('post')) {
			$this->Reporte->create($this->request->data);
			$fieldList = array('carrera_id', 'desde', 'hasta');
			if ($this->Reporte->validates(compact('fieldList'))) {
				$options['data'] = $this->Reporte->data['Reporte'];
			} else {
				$options['data'] = array();
			}
		}

		$carreras = $this->Registro->getCarrerasList();
		if (!$this->request->data) {
			if ($options['data']) {
				$this->request->data['Reporte'] = $options['data'];
			} else {
				$this->request->data = array(
					'Reporte' => array(
						'carrera_id' => key($carreras),
						'desde' => null,
						'hasta' => null,
					)
				);
				$options['data'] = $this->request->data['Reporte'];
			}
		}

		$findOptions = array(
			'conditions' => array(
				'Asignatura.carrera_id' => $options['data']['carrera_id']
			),
			'fields' => array(
				'asignatura_id', 'usuario_id', 'Materia.nombre', 'usuario',
				'SUM(Registro.tipo = 1) as asistencia',
				'SUM(Registro.tipo = 0) as inasistencia',
				'SUM(Registro.tipo = 2) as sin_actividad'
			),
			'group' => array('asignatura_id', 'usuario_id'),
			'recursive' => 0
		);
		if (!empty($options['data']['desde'])) {
			$findOptions['conditions']['CAST(Registro.fecha as DATE) >='] = $options['data']['desde'];
		}
		if (!empty($options['data']['hasta'])) {
			$findOptions['conditions']['CAST(Registro.fecha as DATE) <='] = $options['data']['hasta'];
		}

		$this->set(array(
			'carreras' => $carreras,
			'rows' => $this->Registro->find('all', $findOptions),
			'title_for_layout' => 'Asistencia general - Reportes',
			'title_for_view' => 'Asistencia general'
		));

		$this->Session->write($this->_getSessionKey('Options'), $options);
	}

/**
 * Genera una clave de sesión incluyendo el nombre de la acción actual como prefijo
 *
 * @param string $key Clave
 *
 * @return string Clave
 */
	protected function _getSessionKey($key = '') {
		return sprintf('Reporte.%s%s',
			Inflector::camelize($this->request->action),
			(!empty($key) ? ".$key" : "")
		);
	}

/**
 * Establece la configuración inicial de CakePdf y opciones comunes para los reportes
 *
 * @param string $title Título
 * @param string $orientation Orientación
 *
 * @return void
 */
	protected function _setupCakePdf($title = 'Reporte', $orientation = 'landscape') {
		$this->autoRender = false;

		$isWindows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
		$charset = (!$isWindows ? 'ASCII' : 'ISO-8859-1');
		$date = preg_replace_callback(
			"/[a-zA-Záéíóú]{3,}/u",
			function ($m) {
				return ucfirst($m[0]);
			},
			CakeTime::format(time(), '%A %d de %B de %Y')
		);

		$this->pdfConfig = array(
			'engine' => 'CakePdf.WkHtmlToPdf',
			'options' => array(
				'dpi' => 96,
				'footer-center' => iconv('UTF-8', $charset . '//TRANSLIT', 'Página [frompage] de [topage]'),
				'footer-font-name' => 'Arial',
				'footer-font-size' => '9',
				'footer-line' => false,
				'header-center' => $title,
				'header-font-name' => 'Arial',
				'header-font-size' => '9',
				'header-left' => 'Sistema de Registro de Asistencia y Temas',
				'header-line' => true,
				'header-right' => iconv('UTF-8', $charset . '//TRANSLIT', $date),
				'outline' => true,
				'print-media-type' => false
			),
			'margin' => array(
				'bottom' => 5,
				'left' => 3,
				'right' => 3,
				'top' => 5
			),
			'orientation' => $orientation,
			'page-size' => 'A4'
		);

		if (!Configure::check('CakePdf.binary') && !$isWindows) {
			$this->pdfConfig['binary'] = trim(shell_exec('which wkhtmltopdf'));
		}
	}
}
