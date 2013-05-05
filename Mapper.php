<?php

namespace NiceORM;

use Nette;


class Mapper extends Nette\Object implements IEntityDataAcessor
{

	protected $fieldNames;

	public function __construct(Connection $database, $tableName, array $fieldNames) {
		$this->database = $database;
		$this->tableName = $tableName;
		$this->fieldNames = $fieldNames;
		$this->entityRows = new SplObjectStorage;
		$this->modified = new SplObjectStorage;
	}


	public function get($id)
	{
		$row = $this->createTable()->get($id);
		if (!$row)
			return NULL;
		return $this->create($row);
	}


	public function create(Nette\Database\Table\ActiveRow $row = NULL)
	{
		$entity = $this->entityFactory->invoke();
		if ($row) {
			$this->entityRows[$entity] = $row;
		}
		$this->modified[$entity] = array();
		$entity->injectDataAccessor($this);
		return $entity;
	}


	public function save(Entity $entity)
	{
		$insert = isset($entityRows[$entity]) ? FALSE : TRUE;
		if (!isset($this->modified[$entity])) {
			$this->modified[$entity] = array();
			$entity->injectDataAccessor($this);
		}
		$data = $this->modified[$entity];
		if (!$data && !$insert)
			return;

		if ($insert)
			$this->entityRows[$entity] = $this->createTable()->insert($data);
		else
			$this->createTable()
				->wherePrimary($this->entityRows[$entity]->getPrimary())
				->update($data);

		return $entity;
	}


	public function getFieldNames()
	{
		return array_keys($this->fieldNames);
	}


	public function & getField(Entity $entity, $field)
	{
		if (!isset($this->fieldNames[$field]))
			throw new Nette\InvalidArgumentException("Unknown field \"$field\".");
		if (isset($this->modified[$entity][$field]))
			return $this->modified[$entity][$field];
		if (isset($this->entityRows[$entity]))
			return $this->entityRows[$entity]->$field;
		throw new Nette\InvalidArgumentException("Unknown entity.");
	}


	public function setField(Entity $entity, $field, $value)
	{
		if (!isset($this->fieldNames[$field]))
			throw new Nette\InvalidArgumentException("Unknown field \"$field\".");
		if (!isset($this->modified[$entity]))
			$this->modified[$entity] = array();
		$this->modified[$entity][$field] = $value;
	}


	protected function createTable()
	{
		return $this->database->table($this->tableName);
	}

}
