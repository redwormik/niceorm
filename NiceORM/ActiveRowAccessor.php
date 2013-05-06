<?php

namespace NiceORM;

use Nette,
	Nette\Database\Table\ActiveRow,
	Nette\Database\Table\Selection;


class ActiveRowAccessor extends Nette\Object implements IEntityDataAccessor {

	/** @var Manager */
	protected $manager;

	/** @var ActiveRowMapper */
	protected $mapper;

	/** @var ActiveRow */
	protected $row;


	public function __construct(Manager $manager, ActiveRowMapper $mapper, ActiveRow $row) {
		$this->manager = $manager;
		$this->mapper = $mapper;
		$this->row = $row;
	}


	public function & getField($name) {
		$value = $this->mapper->getField($row, $name, $type);
		if ($value instanceof ActiveRow)
			return $this->manager->createEntity($type, $value);
		if ($value instanceof Selection)
			return $this->manager->createCollection($type, $value);
		return $value;
	}


	public function getFieldNames() {
		return $this->mapper->getFieldNames();
	}

}
