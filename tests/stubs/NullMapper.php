<?php

namespace Test;

use NiceORM, Nette;


class NullMapper extends Nette\Object implements NiceORM\IMapper
{


	public function get($id)
	{
		return NULL;
	}

	public function getAll()
	{
		return new MyArrayIterator( array() );
	}


	public function save(NiceORM\Entity $entity) {}
	public function delete(NiceORM\Entity $entity) {}

}
