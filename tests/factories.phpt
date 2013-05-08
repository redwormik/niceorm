<?php

namespace Test;

use NiceORM,
	Nette,
	Tester,
	Tester\Assert;

require __DIR__ . '/bootstrap.php';


$container = new Nette\DI\Container;
$f1 = new NiceORM\DIEntityFactory(array(), $container);
$f2 = new NiceORM\DICollectionFactory(array(), $container);
$f3 = new NiceORM\DIAccessorFactory(array(), $container);
$f4 = new NiceORM\DIMapperAccessor(array(), $container);

$manager = new NiceORM\Manager($f1, $f2, $f3, $f4);


$f4 = new NiceORM\DIMapperAccessor(array(), $container);
$f1 = new NiceORM\EntityFactory(array());
$f2 = new NiceORM\TableCollectionFactory();
$f3 = new NiceORM\ActiveRowAccessorFactory($f4);

$manager = new NiceORM\Manager($f1, $f2, $f3, $f4);
$f2->injectManager($manager);

