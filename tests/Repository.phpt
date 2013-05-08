<?php

namespace Test;

use NiceORM,
	Nette,
	Tester,
	Tester\Assert;

require __DIR__ . '/bootstrap.php';


$manager = new NiceORM\Manager($f1 = new FooFactory, $f2 = new ArrayIteratorFactory, $f3 = new ArrayAccessorFactory, $f4 = new NullMapperAccessor);

$mapper = new NullMapper;

$repo = new NiceORM\Repository('type', $mapper, $manager);

Assert::same( NULL, $repo->get(42) );
Assert::same( array(), iterator_to_array($repo->getAll()) );

$foo = $repo->create();
Assert::true( $foo instanceof FooEntity );

Assert::null( $repo->save($foo) );
Assert::null( $repo->delete($foo) );


