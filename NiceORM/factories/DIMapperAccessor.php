<?php

namespace NiceORM;

use Nette,
	Nette\DI\Container;


class DIMapperAccessor extends Nette\Object implements IMapperAccessor
{

	protected $services;
	protected $container;


	public function __construct(array $services, Container $container)
	{
		$this->services = $services;
		$this->container = $container;
	}


	public function get($type)
	{
		if (!isset($this->services[$type]))
			throw new Nette\InvalidArgumentException;
		$service = $this->services[$type];
		return $this->container->getService($service);
	}

}
