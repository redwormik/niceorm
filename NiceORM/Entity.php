<?php

namespace NiceORM;

use Nette;


class Entity extends Nette\Object
{

	private $_dataAccessor;
	private $_modified = array();


	public function injectDataAccessor(IFieldAccessor $dataAccessor)
	{
		if ($this->_dataAccessor !== NULL)
			throw new Nette\InvalidStateException('Data acessor already set.');
		$this->_dataAccessor = $dataAccessor;
		foreach ($this->_dataAccessor->getFieldNames() as $name) {
			if (property_exists($this, $name) && !isset($this->_modified[$name])) { // must be present
				$this->$name = $this->_dataAccessor;
			}
		}
	}


	public function getDataModified()
	{
		return $this->_modified;
	}


	protected function dataGet($name)
	{
		if ($this->$name === $this->_dataAccessor)
			$this->$name = $this->_dataAccessor->getField($name);
		return $this->$name;
	}


	protected function dataSet($name, $value)
	{
		$this->$name = $this->_modified[$name] = $value;
		return $this;
	}

}
