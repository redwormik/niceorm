<?php

namespace Test;

use NiceORM,
	Nette;


class ArrayAccessor implements NiceORM\IFieldAccessor
{

	protected $data;


	public function __construct(array $data) {
		$this->data = $data;
	}


	public function getField($name) {
		return $this->data[$name];
	}


	public function getFieldNames() {
		return array_keys($this->data);
	}

}
