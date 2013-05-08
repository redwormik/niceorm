SET @OLD_CHECKS = @@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS = 0;


SET NAMES 'utf8';
SET storage_engine=InnoDB;


DROP DATABASE IF EXISTS `niceORM_test`;
CREATE DATABASE `niceORM_test` CHARACTER SET utf8 COLLATE utf8_czech_ci;
USE `niceORM_test`;


DROP TABLE IF EXISTS `core_diagram`;
CREATE TABLE `core_diagram` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL,
	`project_id` INT UNSIGNED NOT NULL,
	`package_id` INT UNSIGNED,
	`type` VARCHAR (50),
	FOREIGN KEY (`project_id`) REFERENCES `core_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`package_id`) REFERENCES `core_package` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
	UNIQUE (`project_id`, `name`)
);


DROP TABLE IF EXISTS `core_element`;
CREATE TABLE `core_element` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL,
	`project_id` INT UNSIGNED NOT NULL,
	`package_id` INT UNSIGNED,
	`type` VARCHAR (50),
	FOREIGN KEY (`project_id`) REFERENCES `core_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`package_id`) REFERENCES `core_package` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
	UNIQUE (`project_id`, `name`)
	-- package.project_id == project_id
);


DROP TABLE IF EXISTS `core_package`;
CREATE TABLE `core_package` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL,
	`project_id` INT UNSIGNED NOT NULL,
	`parent_id` INT UNSIGNED,
	FOREIGN KEY (`project_id`) REFERENCES `core_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`parent_id`) REFERENCES `core_package` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
);


DROP TABLE IF EXISTS `core_placement`;
CREATE TABLE `core_placement` (
	`diagram_id` INT UNSIGNED NOT NULL,
	`element_id` INT UNSIGNED NOT NULL,
	`posX` INT UNSIGNED NOT NULL,
	`posY` INT UNSIGNED NOT NULL,
	PRIMARY KEY (`diagram_id`, `element_id`),
	FOREIGN KEY (`diagram_id`) REFERENCES `core_diagram` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`element_id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
	-- diagram.project_id == element.project_id
);


DROP TABLE IF EXISTS `core_project`;
CREATE TABLE `core_project` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL
);


DROP TABLE IF EXISTS `core_relation`;
CREATE TABLE `core_relation` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR (50) NOT NULL,
	`start_id` INT UNSIGNED NOT NULL,
	`end_id` INT UNSIGNED NOT NULL,
	`type` VARCHAR (50),
	FOREIGN KEY (`start_id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (`end_id`) REFERENCES `core_element` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
	-- start.project_id == end.project_id
);


INSERT INTO `core_diagram` (`id`, `name`, `project_id`, `package_id`, `type`) VALUES
(1,	'Class diagraaaam',	1,	NULL,	'class'),
(2,	'test',	2,	NULL,	'class'),
(3,	'Test',	1,	NULL,	'class');


INSERT INTO `core_element` (`id`, `name`, `project_id`, `package_id`, `type`) VALUES
(1,	'Class 1',	1,	NULL,	'class'),
(2,	'Class 2',	1,	NULL,	'class'),
(3,	'Note',	1,	NULL,	'note'),
(4,	'Dolor',	1,	NULL,	'class'),
(5,	'Sit',	1,	NULL,	'class'),
(6,	'test',	2,	NULL,	'class');


INSERT INTO `core_placement` (`diagram_id`, `element_id`, `posX`, `posY`) VALUES
(1,	1,	500,	100),
(1,	2,	550,	0),
(1,	3,	250,	250),
(1,	4,	500,	150),
(1,	5,	500,	250),
(3,	3,	100,	100),
(3,	4,	420,	420);

INSERT INTO `core_project` (`id`, `name`) VALUES
(1,	'test 1234'),
(2,	'test 1234'),
(3,	'test 1234');

INSERT INTO `core_relation` (`id`, `name`, `start_id`, `end_id`, `type`) VALUES
(1,	'!!!',	3,	1,	'noteLink'),
(2,	'???',	3,	2,	'noteLink'),
(3,	'is cool with',	1,	2,	'association'),
(4,	'',	2,	4,	'association'),
(5,	'',	4,	5,	'association'),
(6,	'',	5,	1,	'association'),
(7,	'self',	4,	4,	'association');


SET FOREIGN_KEY_CHECKS = @OLD_CHECKS;
