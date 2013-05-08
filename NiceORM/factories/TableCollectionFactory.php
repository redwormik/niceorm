<?php

namespace NiceORM;

use Nette,
	Nette\DI\Container;


class TableCollectionFactory extends Nette\Object implements ICollectionFactory
{

	protected $manager;


	public function injectManager(Manager $manager)
	{
		$this->manager = $manager;
	}


	public function create($type, $data)
	{
		return new TableCollection($type, $data, $this->manager);
	}

}
