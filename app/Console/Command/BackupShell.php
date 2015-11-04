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
App::uses('ConnectionManager', 'Model');

/**
 * Genera una copia de seguridad de la base de datos
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class BackupShell extends AppShell {

/**
 * Configuración
 *
 * @var array
 */
	protected $_config = array();

/**
 * Genera una copia de seguridad de la base de datos
 *
 * @return void
 *
 * @throws RuntimeException Si no puede cargarse el archivo de configuración
 */
	public function main() {
		try {
			if (!Configure::load('backup')) {
				throw new RuntimeException('No fue posible cargar el archivo de configuración.');
			}

			$this->_config = array_merge(
				(array)Configure::read('Backup'),
				ConnectionManager::getDataSource('default')->config
			);
		} catch (Exception $e) {
			$this->error($e->getMessage());
		}

		$filename = $this->__getOutputFile();
		if (!$filename) {
			$this->error(sprintf("La ruta de acceso '%s' no existe o no es accesible.", $this->_config['dest']));
		}

		$command = $this->__getBackupCommand($filename);
		if (!$command) {
			$this->error("La aplicación 'mysqldump' no existe o no es accesible.");
		}

		exec($command, $output, $status);
		if ($status === 0) {
			if ($this->_config['compress']) {
				if ($this->__gzipFile($filename)) {
					unlink($filename);
				}
			}
			$this->out('Se ha realizado la copia de seguridad.');
		} else {
			$this->hr();
			$this->out('No fue posible realizar la copia de seguridad.');
		}
	}

/**
 * Obtiene la ruta de acceso al archivo de la copia de seguridad
 *
 * @return bool|string Ruta de acceso al archivo o `false` en caso contrario
 */
	private function __getOutputFile() {
		if (
			empty($this->_config['dest']) || !is_dir($this->_config['dest']) ||
			!is_writable($this->_config['dest'])
		) {
			return false;
		}

		$this->_config['filename'] = strftime($this->_config['filename']);
		if (!$this->_config['filename']) {
			$this->_config['filename'] = strftime('srat_%Y-%m-%d');
		}

		return rtrim($this->_config['dest'], '/\\') . DS . $this->_config['filename'] . '.sql';
	}

/**
 * Obtiene el comando a ejecutar para realizar la copia de seguridad
 *
 * @param string $filename Ruta de acceso al archivo
 *
 * @return bool|string Comando o `false` en caso contrario
 */
	private function __getBackupCommand($filename) {
		if (
			empty($filename) || empty($this->_config['mysqldump']) ||
			!is_executable($this->_config['mysqldump'])
		) {
			return false;
		}

		$command = array(
			$this->_config['mysqldump']
		);
		if (!empty($this->_config['host'])) {
			$command[] = '--host=' . $this->_config['host'];
		}
		if (!empty($this->_config['port'])) {
			$command[] = '--port=' . $this->_config['port'];
		}
		if (!empty($this->_config['login'])) {
			$command[] = '--user=' . $this->_config['login'];
		}
		if (!empty($this->_config['password'])) {
			$command[] = '--password=' . $this->_config['password'];
		}
		if (!empty($this->_config['options'])) {
			$command[] = $this->_config['options'];
		}
		if (!empty($this->_config['database'])) {
			$command[] = $this->_config['database'];
		}

		return implode($command, ' ') . ' > ' . $filename;
	}

/**
 * Comprime un archivo
 *
 * @param string $filename Ruta de acceso al archivo
 *
 * @return bool `true` en caso exitoso o `false` en caso contrario
 */
	private function __gzipFile($filename) {
		if (!empty($filename)) {
			$zip = new ZipArchive;
			if ($zip->open($filename . '.zip', ZipArchive::OVERWRITE) === true) {
				$zip->addFile($filename, basename($filename));
				return $zip->close();
			}
		}
		return false;
	}

/**
 * getOptionParser
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->description(
			"Crea copias de seguridad de la base de datos.\nConsultar '<info>APP\Config\backup.php</info>' para más información."
		);
		return $parser;
	}
}
