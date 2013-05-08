<?php

namespace NiceORM;

use Nette,
	Nette\DI\Container;


class TableCollectionFactory extends Nette\Object implements ICollectionFactory
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
		$manager = $this->container->getByType('NiceORM\\Manager');
		return new TableCollection($type, $data, $manager);
	}

}
