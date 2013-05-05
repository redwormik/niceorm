<?php

namespace NiceORM;

use Nette;


class Collection extends Nette\Object
{
	/** @var Nette\Database\Table\Selection */
	protected $selection;

	protected $entityClass;


	public function __construct(Nette\Database\Table\Selection $selection, $entityClass) {
		$this->selection = $selection;
		$this->entityClass = $entityClass;
	}
}
