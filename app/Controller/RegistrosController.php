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
		'RequestHandler',
		'Paginator' => array(
			'limit' => 15,
			'maxLimit' => 15,
			'order' => array('Registro.fecha' => 'desc'),
			'recursive' => 0
		)
	);

/**
 * Responde a solicitudes invalidadas por el componente Security
 *
 * @param null|string $type Tipo de error
 *
 * @return void
 */
	public function blackHole($type = null) {
		$this->Session->delete('Reporte');
		parent::blackHole($type);
	}

/**
 * Reporte
 *
 * @return void
 */
	public function admin_reporte() {
		$this->loadModel('Reporte');

		if (!empty($this->request->named['reset'])) {
			$this->Session->delete('Reporte');
			$this->redirect(array('action' => 'reporte'));
		}

		$options = (array)$this->Session->read('Reporte');
		if (!$options) {
			$options = array(
				'data' => array(),
				'paging' => array()
			);
		}

		if ($this->request->ext === 'pdf') {
			return $this->__generateReport($options);
		}

		if ($this->request->is('post')) {
			$this->Reporte->create($this->request->data);
			if ($this->Reporte->validates()) {
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
			}
		}

		$this->Paginator->settings = array_merge(
			$this->Paginator->settings,
			$this->__getFindOptions($options),
			array('limit' => 10, 'maxLimit' => 10)
		);

		$asignaturas = $this->Reporte->getAsignaturas();
		$usuarios = $this->Reporte->getUsuarios($this->request->data['Reporte']['asignatura_id']);

		$this->__setupModelAssociations();
		$this->set(array(
			'asignaturas' => $asignaturas,
			'usuarios' => $usuarios,
			'rows' => $this->Paginator->paginate(),
			'title_for_layout' => 'Reportes - Registros',
			'title_for_view' => 'Reportes'
		));

		if (isset($this->params['paging']['Registro']['order'])) {
			$options['paging']['order'] = $this->params['paging']['Registro']['order'];
		}

		$this->Session->write('Reporte', $options);
	}

/**
 * Establece asociaciones entre modelos necesarias para realizar búsquedas
 *
 * @return void
 */
	private function __setupModelAssociations() {
		$this->Registro->virtualFields = array(
			'asignatura' => $this->Registro->Asignatura->virtualFields['asignatura'],
			'usuario' => $this->Registro->Usuario->virtualFields['nombre_completo']
		);

		$this->Registro->bindModel(array(
			'hasOne' => array(
				'Carrera' => array(
					'className' => 'AsignaturasCarrera',
					'conditions' => 'Carrera.id = Asignatura.carrera_id',
					'foreignKey' => false
				),
				'Materia' => array(
					'className' => 'AsignaturasMateria',
					'conditions' => 'Materia.id = Asignatura.materia_id',
					'foreignKey' => false
				)
			)
		), false);
	}

/**
 * Obtiene las opciones de búsqueda para el reporte
 *
 * @param array $options Opciones
 *
 * @return array
 */
	private function __getFindOptions($options) {
		$result = array(
			'conditions' => array(
				'Registro.tipo' => 1
			),
			'fields' => array(
				'asignatura', 'Usuario.legajo', 'Usuario.apellido',
				'Usuario.nombre', 'tipo', 'fecha', 'entrada', 'salida', 'obs'
			),
			'order' => array('Registro.fecha' => 'desc'),
			'recursive' => 0
		);

		if (!empty($options['data']['asignatura_id'])) {
			$result['conditions']['Registro.asignatura_id'] = $options['data']['asignatura_id'];
		}

		if (!empty($options['data']['usuario_id'])) {
			$result['conditions']['Registro.usuario_id'] = $options['data']['usuario_id'];
		}

		if (!empty($options['data']['desde'])) {
			$result['conditions']['Registro.fecha >='] = $options['data']['desde'];
		}

		if (!empty($options['data']['hasta'])) {
			$result['conditions']['Registro.fecha <='] = $options['data']['hasta'];
		}

		if (isset($options['data']['tipo'])) {
			if ($options['data']['tipo'] === '0') {
				$result['conditions']['Registro.tipo'] = 0;
			} elseif ($options['data']['tipo'] === '2') {
				unset($result['conditions']['Registro.tipo']);
			}
		}

		if (!empty($options['paging']['order'])) {
			$result['order'] = $options['paging']['order'];
		}

		return $result;
	}

/**
 * Genera un reporte en formato PDF
 *
 * @param array $options Condiciones de búsqueda y opciones de ordenamiento
 *
 * @return void
 */
	private function __generateReport($options) {
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

		$title = 'Asistencia';
		if (isset($options['data']['tipo'])) {
			if ($options['data']['tipo'] === '0') {
				$title = 'Inasistencia';
			} elseif ($options['data']['tipo'] === '2') {
				$title .= ' e Inasistencia';
			}
		}

		$this->pdfConfig = array(
			'engine' => 'CakePdf.WkHtmlToPdf',
			'options' => array(
				'dpi' => 96,
				'footer-center' => iconv('UTF-8', $charset . '//TRANSLIT', 'Página [frompage] de [topage]'),
				'footer-font-name' => 'Arial',
				'footer-font-size' => '9',
				'footer-line' => false,
				'header-center' => 'Reporte de ' . $title,
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
			'orientation' => 'landscape',
			'page-size' => 'A4'
		);

		if (!Configure::check('CakePdf.binary') && !$isWindows) {
			$this->pdfConfig['binary'] = trim(shell_exec('which wkhtmltopdf'));
		}

		$data = $options['data'];
		$settings = $this->__getFindOptions($options);
		$this->__setupModelAssociations();

		if (!empty($data['asignatura_id'])) {
			$row = $this->Registro->Asignatura->find('first', array(
				'conditions' => array('Asignatura.id' => $data['asignatura_id']),
				'fields' => array('asignatura'),
				'recursive' => 0
			));
			$data['asignatura'] = $row['Asignatura']['asignatura'];
		}

		if (!empty($data['usuario_id'])) {
			$this->Registro->Usuario->virtualFields = array(
				'nombre_completo' => 'CONCAT("(", Usuario.legajo, ")", " ", Usuario.apellido, ", ", Usuario.nombre)'
			);
			$row = $this->Registro->Usuario->find('first', array(
				'conditions' => array('Usuario.id' => $data['usuario_id']),
				'fields' => array('nombre_completo')
			));
			$data['usuario'] = $row['Usuario']['nombre_completo'];
		}

		$this->set(array(
			'data' => $data,
			'rows' => $this->Registro->find('all', $settings)
		));

		try {
			$this->render();
		} catch (Exception $e) {
			$this->_notify(null, array(
				'message' => 'No fue posible exportar el resultado debido a un error interno.',
				'redirect' => array('action' => 'reporte')
			));
		}
	}
}
