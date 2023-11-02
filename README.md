## Sistema de Registro de Asistencia y Temas

Sistema de registro de asistencia y temas en la UTN Regional Delta que considere la asistencia a materias y casos
especiales como ser laboratorios y talleres.

## Habilitar debug
Para habilitar del debug ir a app/Config/core.php y cambiar el Configure::wirte(debug, 1)

## Error de clave duplicada
registros.UK_REGISTRO

copiar la fecha y eliminar los registros duplicados del tipo 2 para esa fecha:

delete FROM `registros` WHERE fecha = '2023-10-16 00:00:00' and tipo=2
