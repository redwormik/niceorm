<?php

namespace NiceORM;

use Nette;


class ActiveRowAccessorFactory extends Nette\Object implements IAccessorFactory
{

	protected $mappers;


	public function __construct(IMapperAccessor $mappers)
	{
		$this->mappers = $mappers;
	}


	public function create($type, $data)
	{
		return new ActiveRowAccessor($data, $this->mappers->get($type));
	}

}
