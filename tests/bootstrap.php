<?php

if (@!(include __DIR__ . '/../vendor/autoload.php') || @!(include __DIR__ . '/../vendor/nette/tester/Tester/bootstrap.php')) {
	echo 'Install Nette Tester using `composer update --dev`';
	exit(1);
}


define('TEMP_DIR', __DIR__ . '/../tmp/' . getmypid());
@mkdir(dirname(TEMP_DIR)); // @ - directory may already exist
Tester\Helpers::purge(TEMP_DIR);


function id($val) {
	return $val;
}


$cacheStorage = new Nette\Caching\Storages\FileStorage(TEMP_DIR);

$loader = new Nette\Loaders\RobotLoader;
$loader->setCacheStorage($cacheStorage);
$loader->addDirectory(__DIR__ . '/../NiceORM');
$loader->addDirectory(__DIR__ . '/stubs');
$loader->addDirectory(__DIR__ . '/model');
$loader->register();
