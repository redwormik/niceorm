<?php

namespace NiceORM;

use Nette,
	Nette\Database\Table;


class Manager extends Nette\Object
{

	public function createEntity($type, Table\ActiveRow $row = NULL)
	{
		$entity = $this->entityFactory->create($type);
		if ($row) {
			$accessor = $this->createAccessor($type, $row);
			$entity->injectDataAccessor($accessor);
			$this->managed[$entity] = $accessor;
			// save type here?
		}
		return $entity;
	}


	public function createCollection($type, Table\Selection $table = NULL)
	{
		if ($table === NULL)
			$table = $this->createTable($type);
		return new Collection($table, $this);
	}


	protected function createAccessor($type, $row)
	{
		return new ActiveRowAccessor($row, $this, $this->fields[$type], $this->refs[$type], $this->related[$type]);
	}





	public function save(Entity $entity)
	{

	}

}
