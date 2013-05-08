<?php

namespace NiceORM;

use Nette,
	Nette\Database\Connection,
	Nette\Database\Table\Selection;


class Repository extends Nette\Object
{

	protected $type;
	protected $mapper;
	protected $manager;


	public function __construct($type, IMapper $mapper, Manager $manager) {
		$this->type = $type;
		$this->mapper = $mapper;
		$this->manager = $manager;
	}


	public function get($id)
	{
		return $this->mapper->get($id);
	}


	public function getAll()
	{
		return $this->mapper->getAll();
	}


	public function create()
	{
		return $this->manager->createEntity($this->type);
	}


	public function save(Entity $entity)
	{
		$this->mapper->save($entity);
	}


	public function delete(Entity $entity)
	{
		$this->mapper->delete($entity);
	}

}
