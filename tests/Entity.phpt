<?php

namespace Test;

use NiceORM,
	Nette,
	Tester,
	Tester\Assert;

require __DIR__ . '/bootstrap.php';


class FooAccessor implements NiceORM\IFieldAccessor
{
	public $calls = 0;

	/** @return mixed */
	function getField($name){
		$this->calls++;
		$foo = 'foobar';
		return $foo;
	}


	/** @return array() */
	function getFieldNames() {
		return array('foo');
	}
}


Assert::equal(id(new FooEntity)->setFoo(42)->getFoo(), 42);

$foo = new FooEntity;
$foo->foo = 'bar';
Assert::equal($foo->foo, 'bar');
Assert::equal($foo->dataModified, array('foo' => 'bar'));

$bar = new FooEntity;
$bar->injectDataAccessor($accessor = new FooAccessor);
Assert::equal($accessor->calls, 0);
Assert::equal($bar->foo, 'foobar');
Assert::equal($accessor->calls, 1);
$bar->getFoo();
Assert::equal($accessor->calls, 1);

$bar = new FooEntity;
$bar->foo = 'lorem';
$bar->injectDataAccessor($accessor = new FooAccessor);
Assert::equal($bar->foo, 'lorem');
Assert::equal($accessor->calls, 0);
