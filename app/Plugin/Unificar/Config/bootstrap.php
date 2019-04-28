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
App::uses('ConnectionManager', 'Model');

$defaultConfig = ConnectionManager::getDataSource('default')->config;
foreach (array('basicas', 'electrica', 'mecanica', 'sistemas') as $nombre) {
	ConnectionManager::create($nombre, array('database' => 'srat_' . $nombre) + $defaultConfig);
}
