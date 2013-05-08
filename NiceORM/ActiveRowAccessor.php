<?php

namespace NiceORM;

use Nette,
	Nette\Database\Table\ActiveRow,
	Nette\Database\Table\Selection;


class ActiveRowAccessor extends Nette\Object implements IFieldAccessor
{

	/** @var ActiveRow */
	protected $row;

	/** @var ActiveRowMapper */
	protected $mapper;


	public function __construct(ActiveRow $row, ActiveRowMapper $mapper)
	{
		$this->row = $row;
		$this->mapper = $mapper;
	}


	public function getField($name)
	{
		return $this->mapper->getField($this->row, $name);
	}


	public function getFieldNames()
	{
		return $this->mapper->getFieldNames();
	}

}
