<?php

namespace NiceORM;

use Nette,
	Nette\Database\Connection,
	Nette\Database\Table\ActiveRow;


class ActiveRowMapper extends Nette\Object implements IMapper
{

	protected $type;
	protected $tableName;
	protected $fields;
	protected $refs;
	protected $related;

	protected $connection;
	protected $manager;


	public function __construct($type, $tableName, array $fields, array $refs, array $related, Connection $connection, Manager $manager)
	{
		$this->type       = $type;
		$this->tableName  = $tableName;
		$this->fields     = $fields;
		$this->refs       = $refs;
		$this->related    = $related;
		$this->connection = $connection;
		$this->manager    = $manager;
	}


	public function getField(ActiveRow $row, $name)
	{
		if (isset($this->fields[$name])) {
			$column = $this->fields[$name];
			return $row->$column;
		}
		if (isset($this->refs[$name])) {
			list($table, $column, $type) = $this->refs[$name];
			$ref = $row->ref($table, $column);
			return $ref ? $this->manager->createEntity($type, $row) : NULL;
		}
		if (isset($this->related[$name])) {
			list($table, $column, $type) = $this->related[$name];
			return $this->manager->createCollection($type, $row->related($table, $column));
		}
		throw new Nette\InvalidArgumentException;
	}


	public function getFieldNames()
	{
		return array_merge(array_keys($this->fields), array_keys($this->refs), array_keys($this->related));
	}


	/** @return Entity|NULL */
	public function get($id)
	{
		$row = $this->createTable()->get($id);
		return $row ? $this->manager->createEntity($this->type, $row) : NULL;
	}


	/** @return Collection */
	public function getAll()
	{
		return $this->manager->createCollection($this->type, $this->createTable());
	}


	public function save(Entity $entity)
	{
		$data = array();
		foreach ($entity->getDataModified() as $name => $value) {
			if (isset($this->fields[$name])) {
				$column = $this->fields[$name];
				$data[$column] = $value;
				continue;
			}
			if (isset($this->refs[$name])) {
				list($table, $column, $type) = $this->refs[$name];
				if ($value instanceof Entity) {
					$this->manager->getMapper($type)->save($value);
					$value = $this->manager->getEntityData($value)->getPrimary();
				}
				if ($column === NULL)
					list($table, $column) = $this->connection->databaseReflection->getBelongsToReference($this->tableName, $table);
				$data[$column] = $value;
				continue;
			}
			if (isset($this->related[$name])) {
				// TODO
				throw new Nette\NotImplementedException;
			}
			throw new Nette\InvalidArgumentException;
		}
		$row = $this->manager->getEntityData($entity);
		if ($row === NULL) {
			$row = $this->createTable()->insert($data);
			$this->manager->setEntityData($this->type, $entity, $row);
		}
		else {
			$this->createTable()->wherePrimary($row->getPrimary())->update($data);
		}
	}


	public function delete(Entity $entity)
	{
		$row = $this->manager->getEntityData($entity);
		if ($row === NULL)
			return;
		return $this->createTable()->wherePrimary($row->getPrimary())->delete();
	}


	protected function createTable()
	{
		return $this->connection->table($this->tableName);
	}


}
