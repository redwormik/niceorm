<?php

namespace NiceORM;

use Nette,
	Nette\DI\Container;


class EntityFactory extends Nette\Object
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
		if ($this->container->hasService($service)) {
			return $this->container->getService($service)->create($type, $data);
		}
		$method = Container::getMethodName($service, FALSE);
		return $this->container->$method();
	}

}
