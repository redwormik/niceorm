<?php

namespace Test;


use NiceORM,
	Nette;


class Relation extends NiceORM\Entity
{

	protected $id;
	protected $name;


	public function getId()
	{
		return (int) $this->dataGet('id');
	}


	public function getName()
	{
		return $this->dataGet('name');
	}


	public function setName($name)
	{
		$this->dataSet('name', (string) $name);
		return $this;
	}

}
