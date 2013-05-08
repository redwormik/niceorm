<?php

namespace Test;

use NiceORM;


class ArrayAccessorFactory implements NiceORM\IAccessorFactory
{

	public $calls = array();

	public function create($type, $data)
	{
		$this->calls[] = $type;
		return new ArrayAccessor($data);
	}

}


class MyArrayIterator extends \ArrayIterator implements NiceORM\ICollection {}


class ArrayIteratorFactory implements NiceORM\ICollectionFactory
{

	public $calls = array();

	public function create($type, $data)
	{
		$this->calls[] = $type;
		return new MyArrayIterator($data);
	}

}


class FooFactory implements NiceORM\IEntityFactory
{

	public $calls = array();

	public function create($type, $data) {
		$this->calls[] = $type;
		return new FooEntity;
	}
}


class NullMapperAccessor implements NiceORM\IMapperAccessor
{
	public $calls = array();
	protected $mapper;

	public function get($type) {
		$this->calls[] = $type;
		if ($this->mapper === NULL)
			$this->mapper = new NullMapper;
		return $this->mapper;
	}
}
