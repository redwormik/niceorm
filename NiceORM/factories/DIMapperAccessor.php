<?php

namespace NiceORM;


class DIMapperAccessor extends Nette\Object implements IMapperAccessor
{

	protected $container;
	protected $services;


	public function __construct(Container $container, array $services)
	{
		$this->container = $container;
		$this->services = $services;
	}


	public function get($type)
	{
		if (!isset($this->services[$type]))
			throw new Nette\InvalidArgumentException;
		$service = $this->services[$type];
		return $this->container->getService($service);
	}

}
