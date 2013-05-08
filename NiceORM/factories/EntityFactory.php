<?php

namespace NiceORM;

use Nette,
	Nette\DI\Container;


class EntityFactory extends Nette\Object implements IEntityFactory
{

	protected $classes;


	public function __construct(array $classes)
	{
		$this->classes = $classes;
	}


	public function create($type, $data)
	{
		if (!isset($this->classes[$type]))
			throw new Nette\InvalidArgumentException;
		$class = $this->classes[$type];
		return new $class;
	}

}
