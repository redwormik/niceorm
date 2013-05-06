<?php

namespace NiceORM;

use Nette,
	Nette\Database\Table;


class Manager extends Nette\Object
{

	protected $entityFactory;
	protected $accessorFactory;
	protected $rows;


	public function __construct(EntityFactory $entityFactory, AccessorFactory $accessorFactory)
	{
		$this->entityFactory = $entityFactory;
		$this->accessorFactory = $accessorFactory;
		$this->rows = new \SplObjectStorage;
	}


	public function createEntity($type, Table\ActiveRow $row = NULL)
	{
		$entity = $this->entityFactory->create($type, $row);
		if ($row)
			$this->setEntityRow($type, $entity, $row);
		return $entity;
	}


	public function createCollection($type, Table\Selection $table)
	{
		return $this->collectionFactory->create($this, $type, $table);
	}


	public function getEntityRow(Entity $entity)
	{
		return isset($this->row[$entity]) ? $this->row[$entity] : NULL;
	}


	public function setEntityRow($type, Entity $entity, Table\ActiveRow $row)
	{
		$accessor = $this->createAccessor($type, $row);
		$entity->injectDataAccessor($accessor);
		$this->rows[$entity] = $row;
	}


	protected function createAccessor($type, $row)
	{
		return $this->accessorFactory->create($this, $type, $row);
	}

}
