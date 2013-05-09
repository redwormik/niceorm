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


	public function create($data = NULL)
	{
		$entity = $this->manager->createEntity($this->type);
		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}
		return $entity;
	}


	public function save(Entity $entity = NULL, $data = NULL)
	{
		if ($entity === NULL)
			$entity = $this->create();
		if ($data !== NULL) {
			foreach ($data as $key => $value)
				$entity->$key = $value;
		}
		$this->mapper->save($entity);
	}


	public function delete(Entity $entity)
	{
		$this->mapper->delete($entity);
	}

}
