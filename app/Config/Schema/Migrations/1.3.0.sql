CREATE TABLE `periodos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `desde` date NOT NULL,
  `hasta` date NOT NULL,
  `obs` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_PERIODO` (`desde`,`hasta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `cargos` ADD COLUMN `created` DATE NULL AFTER `resolucion`;
