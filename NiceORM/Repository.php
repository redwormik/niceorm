<?php

namespace NiceORM;

use Nette,
	Nette\Database\Connection,
	Nette\Database\Table\Selection;


class Repository extends Nette\Object
{

	protected $manager;
	protected $mapper;
	protected $type;


	public function __construct(Manager $manager, Mapper $mapper, $type) {
		$this->manager = $manager;
		$this->mapper = $mapper;
		$this->type = $type;
	}


	public function get($id)
	{
		$row = $this->mapper->get($id);
		return $row ? $this->manager->createEntity($this->type, $row) : NULL;
	}


	public function getAll()
	{
		return $this->manager->createCollection($this->type, $this->mapper->createTable());
	}


	public function create()
	{
		return $this->manager->createEntity($this->type);
	}


	public function save(Entity $entity)
	{
		$row = $this->manager->getEntityRow($entity);
		if ($row !== NULL) {
			$this->mapper->update($row->getPrimary(), $entity->getModified());
		}
		$row = $this->mapper->insert($entity->getModified());
		$this->manager->setEntityRow();
	}


	public function delete(Entity $entity)
	{
		$primary = $this->manager->getPrimary($entity);
		if ($primary === NULL)
			return FALSE;
		return $this->mapper->delete($primary);
	}

}
