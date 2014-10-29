<?php
/**
 * Sistema de Registro de Asistencia y Temas
 *
 * (c) Universidad Tecnológica Nacional - Facultad Regional Delta
 *
 * Este archivo está sujeto a los términos y condiciones descritos
 * en el archivo licencia.txt que acompaña a este software.
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */

/**
 * Dependencias
 */
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('CakeSession', 'Model/Datasource');

/**
 * Usuario
 *
 * @author Jorge Alberto Cricelli <jacricelli@gmail.com>
 */
class Usuario extends AppModel {

/**
 * Comportamientos
 *
 * @var array
 */
	public $actsAs = array(
		'Search.Searchable'
	);

/**
 * belongsTo
 *
 * @var array
 */
	public $belongsTo = array(
		'Rol' => array(
			'className' => 'UsuariosRol',
			'foreignKey' => 'rol_id'
		)
	);

/**
 * Nombre del campo utilizado
 * por el tipo de búsqueda `list`
 *
 * @var string
 */
	public $displayField = 'nombre_completo';

/**
 * Campos de búsqueda
 *
 * @var array
 */
	public $filterArgs = array(
		'buscar' => array(
			'field' => array('legajo', 'apellido', 'nombre'),
			'type' => 'like'
		)
	);

/**
 * hasMany
 *
 * @var array
 */
	public $hasMany = array(
		'Cargo'
	);

/**
 * Reglas de validación
 *
 * @var array
 */
	public $validate = array(
		'legajo' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'range' => array(
				'rule' => array('range', 0, 16777216),
				'last' => true,
				'message' => 'El valor ingresado no es válido'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'El valor ingresado ya se encuentra en uso'
			)
		),
		'old_password' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'validatePassword' => array(
				'rule' => 'validatePassword',
				'message' => 'El valor ingresado no es correcto'
			)
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'format' => array(
				'rule' => '/^.*(?=.{6,})(?=.*\d)(?=.*[ÁÉÍÓÚÑáéíóúñA-Za-z]).*$/u',
				'message' => 'El valor ingresado no es válido'
			)
		),
		'new_password' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'format' => array(
				'rule' => '/^.*(?=.{6,})(?=.*\d)(?=.*[ÁÉÍÓÚÑáéíóúñA-Za-z]).*$/u',
				'message' => 'El valor ingresado no es válido'
			)
		),
		'reset' => array(
			'rule' => array('inList', array('0', '1')),
			'required' => true,
			'allowEmpty' => true,
			'message' => ''
		),
		'rol_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('validateExists', 'Rol'),
				'message' => 'El valor seleccionado no existe'
			)
		),
		'estado' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'exists' => array(
				'rule' => array('inList', array('0', '1')),
				'message' => 'El valor seleccionado no es válido'
			)
		),
		'apellido' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 25),
				'message' => 'El valor de este campo no debe superar los 25 caracteres'
			)
		),
		'nombre' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'required' => true,
				'allowEmpty' => false,
				'last' => true,
				'message' => 'Este campo no puede estar vacío'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 40),
				'message' => 'El valor de este campo no debe superar los 40 caracteres'
			)
		)
	);

/**
 * Campos virtuales
 *
 * @var array
 */
	public $virtualFields = array(
		'nombre_completo' => 'CONCAT(Usuario.nombre, " ", Usuario.apellido)'
	);

/**
 * beforeValidate
 *
 * @param array $options Opciones
 *
 * @return boolean `true` para continuar la operación de validación o `false` para cancelarla
 */
	public function beforeValidate($options = array()) {
		if (!$this->id) {
			$this->validator()->getField('new_password')->getRule('notEmpty')->required = false;
			$this->validator()->getField('old_password')->getRule('notEmpty')->required = false;
		} else {
			if (!isset($this->data[$this->alias]['old_password']) && !isset($this->data[$this->alias]['new_password'])) {
				$this->validator()->getField('new_password')->getRule('notEmpty')->required = false;
				$this->validator()->getField('old_password')->getRule('notEmpty')->required = false;

				if (empty($this->data[$this->alias]['password'])) {
					$this->validator()->getField('password')->getRule('notEmpty')->allowEmpty = true;
				}
			} elseif (isset($this->data[$this->alias]['old_password'])) {
				$this->validator()->getField('new_password')->getRule('notEmpty')->required = false;

				if (empty($this->data[$this->alias]['old_password']) && empty($this->data[$this->alias]['password'])) {
					$this->validator()->getField('old_password')->getRule('notEmpty')->allowEmpty = true;
					$this->validator()->getField('password')->getRule('notEmpty')->allowEmpty = true;
				}
			} else {
				$this->validator()->getField('old_password')->getRule('notEmpty')->required = false;
				$this->validator()->getField('password')->getRule('notEmpty')->required = false;
			}
		}

		if (!empty($this->data[$this->alias]['reset'])) {
			$this->data[$this->alias]['password'] = 'abc12345';
		}

		return true;
	}

/**
 * beforeSave
 *
 * @param array $options Opciones
 *
 * @return boolean `true` para continuar la operación de guardado o `false` para cancelarla
 */
	public function beforeSave($options = array()) {
		if (!empty($this->data[$this->alias]['new_password'])) {
			$this->data[$this->alias]['password'] = $this->data[$this->alias]['new_password'];
			unset($this->data[$this->alias]['new_password']);
		}

		if (!empty($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = (new BlowfishPasswordHasher())->hash(
				$this->data[$this->alias]['password']
			);
		} else {
			unset($this->data[$this->alias]['password']);
		}

		return true;
	}

/**
 * afterSave
 *
 * @param boolean $created Indica si se ha creado un registro
 * @param array $options Opciones
 *
 * @return void
 */
	public function afterSave($created, $options = array()) {
		if (!$created && $this->id == AuthComponent::user('id')) {
			$user = $this->read();
			unset($user[$this->alias]['password']);
			CakeSession::write(AuthComponent::$sessionKey, $user[$this->alias]);
		}
	}

/**
 * Valida que un valor coincide con la contraseña actual de un usuario
 *
 * @param array $check Nombre del campo y su valor
 *
 * @return boolean `true` en caso exitoso o `false` en caso contrario
 */
	public function validatePassword($check) {
		if (!empty($check)) {
			return (new BlowfishPasswordHasher())->check(
				current($check),
				$this->field('password')
			);
		}
		return false;
	}

/**
 * Obtiene todos los cargos asociados a un usuario en el día de la fecha
 *
 * @param mixed $id Identificador
 *
 * @return array Cargos
 */
	public function getCargos($id = null) {
		$out = array();

		if ($id) {
			if (is_array($id)) {
				$id = $id[0];
			}
			$this->id = $id;
		}
		$id = $this->id;

		if ($id && $this->exists()) {
			$this->Cargo->unbindModel(array('belongsTo' => array('Dedicacion', 'Usuario')));
			$this->Cargo->bindModel(array(
				'hasOne' => array(
					'Registro' => array(
						'conditions' => array(
							'Registro.fecha = CURDATE()',
							'Registro.cargo_id = Cargo.id',
							'Registro.tipo' => 1
						),
						'foreignKey' => false
					),
					'Carrera' => array(
						'className' => 'AsignaturasCarrera',
						'conditions' => 'Carrera.id = Asignatura.carrera_id',
						'foreignKey' => false
					),
					'Horario' => array(
						'conditions' => 'Horario.asignatura_id = Asignatura.id',
						'foreignKey' => false
					),
					'Materia' => array(
						'className' => 'AsignaturasMateria',
						'conditions' => 'Materia.id = Asignatura.materia_id',
						'foreignKey' => false
					)
				)
			));

			$this->Cargo->virtualFields = array(
				'asignatura' => $this->Cargo->Asignatura->virtualFields['asignatura']
			);

			$rows = $this->Cargo->find('all', array(
				'fields' => array(
					'Registro.*',
					'Cargo.asignatura',
					'Grado.nombre',
					'Tipo.nombre'
				),
				'conditions' => array(
					'Cargo.usuario_id' => $id,
					'Horario.dia' => date('w')
				),
				'recursive' => 0
			));

			foreach ($rows as $rid => $row) {
				if (empty($row['Registro']['cargo_id'])) {
					$row['Registro']['fecha'] = date('Y-m-d');
					$row['Registro']['cargo_id'] = $row['Cargo']['id'];
					$row['Registro']['tipo'] = 1;
				}

				$out['Registro'][$rid] = $row['Registro'];
				$out['Cargo'][$rid]['asignatura'] = $row['Cargo']['asignatura'];
				$out['Grado'][$rid]['nombre'] = $row['Grado']['nombre'];
				$out['Tipo'][$rid]['nombre'] = $row['Tipo']['nombre'];
			}
		}

		return $out;
	}
}
