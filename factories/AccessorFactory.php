<?php

namespace NiceORM;

use Nette,
	Nette\DI\Container;


class AccessorFactory extends Nette\Object
{

	protected $container;
	protected $services;


	public function __construct(Container $container, array $services)
	{
		$this->container = $container;
		$this->services = $services;
	}


	public function create(Manager $manager, $type, $data)
	{
		if (!isset($this->services[$type]))
			throw new Nette\InvalidArgumentException;
		$method = Container::getMethodName($this->services[$type], FALSE);
		return $this->container->$method($manager, $data);
	}

}
