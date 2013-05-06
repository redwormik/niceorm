<?php

namespace NiceORM;

use Nette,
	Nette\Database\Table\ActiveRow;


class ActiveRowMapper extends Nette\Object implements IMapper
{

	protected $connection;
	protected $type;
	protected $fields;
	protected $refs;
	protected $related;


	public function __construct(Connection $connection, $tableName, array $fields, array $refs, array $related)
	{
		$this->connection = $connection;
		$this->type       = $type;
		$this->fields     = $fields;
		$this->refs       = $refs;
		$this->related    = $related;
	}


	public function & getField(ActiveRow $row, $name, &$type)
	{
		if (isset($this->fields[$name])) {
			$column = $this->fields[$name];
			return $row->$column;
		}
		if (isset($this->refs[$name])) {
			list($table, $column, $type) = $this->refs[$name];
			$ref = $row->ref($table, $column);
			return $ref ?: NULL;
		}
		if (isset($this->related[$name])) {
			list($table, $column, $type) = $this->related[$name];
			return  $row->related($table, $column);
		}
		throw new Nette\InvalidArgumentException;
	}


	public function getFieldNames()
	{
		return array_merge(array_keys($this->fields), array_keys($this->refs), array_keys($this->related));
	}


	/** @return ActiveRow|NULL */
	public function get($id)
	{
		return $this->createTable()->get($id);
	}


	/** @return Selection */
	public function createTable()
	{
		return $this->connection->table($this->tableName);
	}


	public function save($data, $id = NULL)
	{
		$row = array();
		foreach ($data as $name => $value) {
			if (isset($this->fields[$name])) {
				$column = $this->fields[$name];
				$row[$column] = $value;
				continue;
			}
			if (isset($this->refs[$name])) {
				// TODO
				continue;
			}
			if (isset($this->related[$name])) {
				// TODO
				continue;
			}
			throw new Nette\InvalidArgumentException;
		}
		if ($id === NULL)
			return $this->createTable()->insert($row);
		return $this->createTable()->wherePrimary($id)->update($data);
	}


	public function delete($id)
	{
		return $this->createTable()->wherePrimary($id)->delete();
	}


}
