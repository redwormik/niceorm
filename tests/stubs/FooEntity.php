<?php

namespace Test;

use NiceORM,
	Nette;


class FooEntity extends NiceORM\Entity
{

	protected $foo;

	public function getFoo()
	{
		return $this->dataGet('foo');
	}

	public function setFoo($foo)
	{
		return $this->dataSet('foo', $foo);
	}
}
