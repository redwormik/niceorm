<?php

namespace Test;

use NiceORM,
	Nette,
	Tester,
	Tester\Assert;

require __DIR__ . '/bootstrap.php';


$manager = new NiceORM\Manager($f1 = new FooFactory, $f2 = new ArrayIteratorFactory, $f3 = new ArrayAccessorFactory, $f4 = new NullMapperAccessor);

Assert::true( ($foo = $manager->createEntity('one')) instanceof NiceORM\Entity );
Assert::true($foo instanceof FooEntity);
Assert::equal( $manager->getEntityData($foo), NULL );
$manager->setEntityData('two', $foo, $data = array('lorem' => 'ipsum', 'foo' => 'bar'));
Assert::same( $manager->getEntityData($foo), $data );
Assert::equal( $foo->foo, 'bar' );


$bar = $manager->createEntity('three', $data = array('foo' => 42, 'dolor' => 'sit'));
Assert::true($bar instanceof FooEntity);
Assert::equal( $data, $manager->getEntityData($bar) );
Assert::equal( 42, $bar->foo );


$collection = $manager->createCollection( 'four', $data = array('lorem' => 'ipsum', 'foo' => 'bar') );
Assert::true($collection instanceof MyArrayIterator);
Assert::equal(2, count($collection));
Assert::equal( $data, iterator_to_array($collection) );


$mapper = $manager->getMapper('five');
Assert::true($mapper instanceof NullMapper);

Assert::equal( array('one', 'three'), $f1->calls);
Assert::equal( array('four'), $f2->calls);
Assert::equal( array('two', 'three'), $f3->calls);
Assert::equal( array('five'), $f4->calls);


