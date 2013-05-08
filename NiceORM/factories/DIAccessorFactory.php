<?php

namespace NiceORM;

use Nette,
	Nette\DI\Container;


class DIAccessorFactory extends Nette\Object implements IEntityFactory
{

	protected $services;
	protected $container;


	public function __construct(array $services, Container $container)
	{
		$this->services = $services;
		$this->container = $container;
	}


	public function create($type, $data)
	{
		if (!isset($this->services[$type]))
			throw new Nette\InvalidArgumentException;
		$service = $this->services[$type];
		$method = Container::getMethodName($service, FALSE);
		return $this->container->$method($data);
	}

}
