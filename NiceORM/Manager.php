<?php

namespace NiceORM;

use Nette,
	Nette\Database\Table;


class Manager extends Nette\Object
{

	protected $entityFactory;
	protected $collectionFactory;
	protected $accessorFactory;
	protected $mapperAccessor;
	protected $data;


	public function __construct(IEntityFactory $entityFactory, ICollectionFactory $collectionFactory, IAccessorFactory $accessorFactory, IMapperAccessor $mapperAccessor)
	{
		$this->entityFactory     = $entityFactory;
		$this->collectionFactory = $collectionFactory;
		$this->accessorFactory   = $accessorFactory;
		$this->mapperAccessor    = $mapperAccessor;
		$this->data              = new \SplObjectStorage;
	}


	public function createEntity($type, $data = NULL)
	{
		$entity = $this->entityFactory->create($type, $data);
		if (!$entity instanceof Entity)
			throw new Nette\UnexpectedValueException;
		if ($data)
			$this->setEntityData($type, $entity, $data);
		return $entity;
	}


	public function createCollection($type, $data)
	{
		$collection = $this->collectionFactory->create($type, $data);
		if (!$collection instanceof ICollection)
			throw new Nette\UnexpectedValueException;
		return $collection;
	}


	public function getEntityData(Entity $entity)
	{
		return isset($this->data[$entity]) ? $this->data[$entity] : NULL;
	}


	public function setEntityData($type, Entity $entity, $data)
	{
		$accessor = $this->createAccessor($type, $data);
		$entity->injectDataAccessor($accessor);
		$this->data[$entity] = $data;
	}


	public function getMapper($type)
	{
		$mapper = $this->mapperAccessor->get($type);
		if (!$mapper instanceof IMapper)
			throw new Nette\UnexpectedValueException;
		return $mapper;
	}


	protected function createAccessor($type, $data)
	{
		return $this->accessorFactory->create($type, $data);
	}

}
