<?php

namespace NiceORM;

use Nette,
	Nette\Database\Connection,
	Nette\Database\Table\Selection;


class Repository extends Nette\Object
{

	/** @var Connection */
	protected $database;

	protected $tableName;

	public function __construct(Connection $database, $tableName) {
		$this->database = $database;
		$this->tableName = $tableName;
	}


	public function get($id)
	{
		$row = $this->getTable()->get($id);
		if (!$row)
			return NULL;
		return $this->create($row);
	}


	public function create($row = NULL)
	{
		$entity = $this->entityFactory->invoke();
		if ($row) {
			$accessor = new ActiveRowAccessor($row);
			$entity->injectAccessor($row);
		}
		return $entity;
	}


	public function save(Entity $entity)
	{
		if (!isset($this->accessors[$entity])) {
			$accessor = new NullAccessor;
			$entity->injectAccessor($accessor);
			$row = $this->getTable()->
			$this->accessors[$entity] = $accessor;
		}
	}

}
