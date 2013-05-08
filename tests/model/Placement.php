<?php

namespace Test;


use NiceORM,
	Nette;


class Placement extends NiceORM\Entity
{

	protected $diagram;
	protected $element;


	public function getDiagram()
	{
		return $this->dataGet('diagram');
	}


	public function getElement()
	{
		return $this->dataGet('element');
	}

}
