<?php

namespace NiceORM;

use Nette,
	Nette\DI\Container;


class DIEntityFactory extends Nette\Object implements IEntityFactory
{

	protected $container;
	protected $services;


	public function __construct(Container $container, array $services)
	{
		$this->container = $container;
		$this->services = $services;
	}


	public function create($type, $data)
	{
		if (!isset($this->services[$type]))
			throw new Nette\InvalidArgumentException;
		$service = $this->services[$type];
		$method = Container::getMethodName($service, FALSE);
		return $this->container->$method();
	}

}
