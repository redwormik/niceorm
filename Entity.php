<?php

namespace NiceORM;

use Nette;


class Entity extends Nette\Object
{

	private $_dataAccessor;
	private $_modified = array();


	public function injectDataAcessor(IEntityDataAcessor $dataAccessor) {
		if ($this->_dataAccessor !== NULL)
			throw new Nette\InvalidStateException('Data acessor already set.');
		$this->_dataAccessor = $dataAccessor;
		foreach ($this->_dataAccessor->getFieldNames() as $name) {
			if (property_exists($this, $name) && !isset($this->modified[$name])) { // must be present
				$this->$name = $this->_dataAccessor;
			}
		}
	}


	protected function & dataGet($name) {
		if ($this->$name === $this->_dataAccessor)
			$this->$name = $this->_dataAccessor->get($name);
		return $this->$name;
	}


	protected function dataSet($name, $value) {
		$this->$name = $this->_modified[$name] = $value;
		return $this;
	}

}
