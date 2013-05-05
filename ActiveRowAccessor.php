<?php

namespace NiceORM;

use Nette,
	Nette\Database\Table\ActiveRow;


class ActiveRowAccessor extends Nette\Object implements IEntityDataAccessor {

	/** @var Manager */
	protected $manager;

	/** @var ActiveRow */
	protected $row;

	/** @var array */
	protected $fields;
	protected $refs;
	protected $related;

	protected $modified = array();


	public function __construct(ActiveRow $row, Manager $manager,array $fields, array $refs, array $related) {
		$this->manager = $manager;
		$this->row = $row;
		$this->fields = $fields;
		$this->refs = $refs;
		$this->related = $related;
	}


	public function & getField($name) {
		if (isset($this->fields[$name])) {
			$column = $this->fields[$name];
			return $this->row->$column;
		}

		if (isset($this->refs[$name])) {
			list($table, $column, $type) = $this->refs[$name];
			$row = $this->row->ref($table, $column);
			if (!$row)
				return NULL;
			return $this->manager->createEntity($type, $row);
		}

		if (isset($this->related[$name])) {
			list($table, $column, $type) = $this->refs[$name];
			$table = $this->row->related($table, $column);
			return $this->manager->createCollection($type, $table);
		}

		throw new Nette\InvalidArgumentException;
	}


	public function setField($name, $value) {
		if (isset($this->fields[$name]))
			$this->modified[$name] = $value;
		if (isset($this->refs[$name])) {
			// list($table, $column) = $this->refs[$name];
			// $this->modified[$column] = $value;
			return; // TODO
		}
		if (isset($this->related[$name])) {
			return; // TODO
		}
		throw new Nette\InvalidArgumentException;
	}


	public function getFieldNames() {
		return array_merge(array_keys($this->fields), array_keys($this->refs), array_keys($this->related));
	}


	public function getModified() {
		return $this->modified;
	}

}
