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
App::uses('AppShell', 'Console/Command');
App::uses('CakePdf', 'CakePdf.Pdf');
App::uses('CakeTime', 'Utility');
App::uses('Hash', 'Utility');

/**
 * Reportes
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class ReportesShell extends AppShell {

/**
 * Configuración de CakePdf
 *
 * @var array
 */
	protected $_pdfConfig = array(
		'binary' => '',
		'engine' => 'CakePdf.WkHtmlToPdf',
		'options' => array(
			'dpi' => 96,
			'footer-center' => 'Página [frompage] de [topage]',
			'footer-font-name' => 'Arial',
			'footer-font-size' => '9',
			'footer-line' => false,
			'header-center' => 'Reporte de Asistencia e Inasistencia',
			'header-font-name' => 'Arial',
			'header-font-size' => '9',
			'header-left' => 'Sistema de Registro de Asistencia y Temas',
			'header-line' => true,
			'header-right' => '',
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

/**
 * Opciones
 *
 * @var array
 */
	protected $_options = array(
		'output' => null,
		'legajo' => null,
		'desde' => null,
		'hasta' => null,
		'users' => array(),
		'find' => array(
			'conditions' => array(),
			'fields' => array('asignatura', 'tipo', 'fecha', 'entrada', 'salida', 'obs'),
			'order' => array('fecha' => 'desc'),
			'recursive' => 0
		)
	);

/**
 * Modelos
 *
 * @var array
 */
	public $uses = array('Registro', 'Usuario');

/**
 * Arranque
 *
 * @return void
 */
	public function startup() {
		if (!$this->_buildUsersList()) {
			$this->error('No se han encontrado usuarios.');
		}

		$this->_validateOptionOutput();
		$this->_validateOptionLegajo();
		$this->_validateOptionsDesdeHasta();

		$isWindows = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
		$charset = (!$isWindows ? 'ASCII' : 'ISO-8859-1');
		$date = preg_replace_callback(
			"/[a-zA-Záéíóú]{3,}/u",
			function ($m) {
				return ucfirst($m[0]);
			},
			CakeTime::format(time(), '%A %d de %B de %Y')
		);

		if (!$isWindows && !Configure::check('CakePdf.binary')) {
			$this->_pdfConfig['binary'] = trim(shell_exec('which wkhtmltopdf'));
		}
		$this->_pdfConfig['options']['footer-center'] = iconv(
			'UTF-8',
			$charset . '//TRANSLIT',
			$this->_pdfConfig['options']['footer-center']
		);
		$this->_pdfConfig['options']['header-right'] = iconv(
			'UTF-8',
			$charset . '//TRANSLIT',
			$date
		);
	}

/**
 * Genera reportes en formato PDF
 *
 * Uso:
 * cake reportes generar [opciones]
 *
 * Opciones:
 *
 * --dir, -d      Ruta de acceso al directorio donde se van a guardar los archivos PDF
 * --legajo, -l   Número de legajo o múltiples separados por comas. Si se
 * 				  omite esta opción, se exportarán todos los registros
 * --desde, -f    Incluir registros desde la fecha (YYYY-MM-DD)
 * --hasta, -t    Incluir registros hasta la fecha (YYYY-MM-DD)
 *
 * @return void
 */
	public function generar() {
		if ($this->_confirmReportTask()) {
			$pdf = new CakePdf($this->_pdfConfig);
			$pdf->template('reporte', 'default');

			if (empty($this->_options['desde'])) {
				$this->_options['desde'] = $this->Registro->getFirstPresenceDate();
			}
			if (empty($this->_options['hasta'])) {
				$this->_options['hasta'] = date('Y-m-d');
			}

			$inc = 0;
			$total = count($this->_options['legajo']);
			foreach ($this->_options['legajo'] as $user) {
				$this->overwrite(sprintf('Generando reporte %d de %d...', ++$inc, $total), 0);

				$this->_options['find']['conditions']['Registro.usuario_id'] = (int)$user['id'];
				$pdf->viewVars(array(
					'rows' => $this->Registro->find('all', $this->_options['find']),
					'user' => $user,
					'desde' => $this->_options['desde'],
					'hasta' => $this->_options['hasta']
				));
				$pdf->write(sprintf('%s%d.pdf', $this->_options['output'], $user['legajo']));
			}
			$this->out();
		}
	}

/**
 * getOptionParser
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->addSubcommand('generar', array(
			'help' => 'Genera reportes en formato PDF',
			'parser' => array(
				'options' => array(
					'dir' => array(
						'help' => 'Ruta de acceso al directorio donde se van a guardar los archivos PDF',
						'short' => 'd'
					),
					'legajo' => array(
						'help' => 'Número de legajo o múltiples separados por comas. Si se omite esta opción, se exportarán todos los registros',
						'short' => 'l'
					),
					'desde' => array(
						'help' => 'Incluir registros desde la fecha (YYYY-MM-DD)',
						'short' => 'f'
					),
					'hasta' => array(
						'help' => 'Incluir registros hasta la fecha (YYYY-MM-DD)',
						'short' => 't'
					)
				)
			)
		))->description(
			'Generación de reportes'
		);
		return $parser;
	}

/**
 * Construye una lista con los datos de los usuarios que se encuentran en la tabla de registros
 *
 * @return bool `true` en caso exitoso o `false` en caso contrario
 */
	protected function _buildUsersList() {
		$users = $this->Registro->getUsuariosList();
		if (!empty($users)) {
			$users = $this->Usuario->find('all', array(
				'conditions' => array('id' => array_keys($users)),
				'fields' => array('id', 'legajo', 'docente')
			));
			$this->_options['users'] = array_combine(
				Hash::extract($users, '{n}.Usuario.legajo'),
				Hash::extract($users, '{n}.Usuario')
			);
			return true;
		}
		return false;
	}

/**
 * Pide al usuario confirmación para ejecutar la generación de reportes
 *
 * @return bool `true` en caso exitoso o `false` en caso contrario
 */
	protected function _confirmReportTask() {
		$inc = 0;
		$this->out('Se generarán reportes para los siguientes docentes:', 2);
		foreach ($this->_options['legajo'] as $user) {
			$this->out(sprintf('%d. %s', ++$inc, $user['docente']));
		}
		$this->out();
		$option = strtoupper($this->in(
			'¿Está seguro que desea continuar?',
			array('S', 'N'),
			'S'
		));
		return $option === 'S';
	}

/**
 * Valida la opción `output`
 *
 * @return void
 */
	protected function _validateOptionOutput() {
		$this->_options['output'] = rtrim($this->param('dir'), '\\/') . DS;
		if (empty($this->_options['output']) === DS) {
			$this->error('Especifique la ruta de acceso al directorio donde se van a guardar los archivos.');
		} elseif (!is_dir($this->_options['output']) || !is_writable($this->_options['output'])) {
			$this->error('La ruta de acceso especificada no existe o no posee permisos de escritura.');
		}
	}

/**
 * Valida la opción `legajo`
 *
 * @return void
 */
	protected function _validateOptionLegajo() {
		$this->_options['legajo'] = $this->param('legajo');
		if (!empty($this->_options['legajo'])) {
			$this->_options['legajo'] = array_intersect_key(
				$this->_options['users'],
				array_flip(array_unique(explode(',', $this->_options['legajo'])))
			);
			if (empty($this->_options['legajo'])) {
				$this->error('No se han encontrado usuarios.');
			}
		} else {
			$this->_options['legajo'] = $this->_options['users'];
		}
	}

/**
 * Valida las opciones `desde` y `hasta`
 *
 * @return void
 */
	protected function _validateOptionsDesdeHasta() {
		$this->_options['desde'] = $this->param('desde');
		if (!empty($this->_options['desde'])) {
			if ($this->_checkDate($this->_options['desde'])) {
				$this->_options['find']['conditions']['CAST(Registro.fecha as DATE) >='] = $this->_options['desde'];
			} else {
				$this->error('La fecha especificada en la opción <info>`desde`</info> no es válida.');
			}
		}

		$this->_options['hasta'] = $this->param('hasta');
		if (!empty($this->_options['hasta'])) {
			if ($this->_checkDate($this->_options['hasta'])) {
				$this->_options['find']['conditions']['CAST(Registro.fecha as DATE) <='] = $this->_options['hasta'];
			} else {
				$this->error('La fecha especificada en la opción <info>`hasta`</info> no es válida.');
			}
		}

		if (
			!empty($this->_options['desde']) && !empty($this->_options['hasta']) &&
			(strtotime($this->_options['desde']) > strtotime($this->_options['hasta']))
		) {
			$error = 'La fecha especificada en la opción <info>`desde`</info> debe ser menor o igual ';
			$error .= 'que la especificada en la opción <info>`hasta`</info>.';
			$this->error($error);
		}
	}

/**
 * Comprueba que una fecha es válida
 *
 * @param string $date Fecha en formato YYYY-MM-DD
 *
 * @return bool `true` en caso exitoso o `false` en caso contrario
 */
	protected function _checkDate($date) {
		if (!empty($date)) {
			$date = explode('-', $date);
			if (count($date) == 3) {
				return checkdate((int)$date[1], (int)$date[2], (int)$date[0]);
			}
		}
		return false;
	}
}
