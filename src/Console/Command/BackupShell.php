<?php
/**
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */

/**
 * Dependencias
 */
App::uses('AppShell', 'Console');
App::uses('ConnectionManager', 'Model');

/**
 * Genera una copia de seguridad de la base de datos
 *
 * @author Jorge Alberto Cricelli <jalberto.cr@live.com>
 */
class BackupShell extends AppShell {

/**
 * main()
 *
 * @return void
 */
	public function main() {
	}

/**
 * Comprime un archivo
 *
 * @param string $filename Ruta de acceso al archivo
 *
 * @return boolean `true` en caso exitoso o `false` en caso contrario
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
}
