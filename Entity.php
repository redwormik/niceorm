<?php

namespace NiceORM;

use Nette;


class Entity extends Nette\Object
{

	private $_dataAccessor;
	private $_data = array();
	private $_dataFields = array();


	public function injectDataAcessor(IEntityDataAcessor $dataAccessor) {
		if ($this->_dataAccessor !== NULL)
			throw new Nette\InvalidStateException('Data acessor already set.');
		$this->_dataAccessor = $dataAccessor;
		foreach ($this->_dataAccessor->getFieldNames() as $name) {
			if (property_exists($this, $name) && !isset($this->_dataFields[$name])) { // must be present
				$value = $this->$name;
				if ($value !== NULL) { // "outject" already set value; TODO: default values
					$this->_data[$name] = $value;
					$this->_dataAccessor->setField($name, $value);
				}
				$this->_dataFields[$name] = TRUE;
				unset($this->$name);
			}
		}
	}


	protected function & dataGet($name) {
		if (isset($this->_dataFields[$name])) {
			if (!array_key_exists($name, $this->_data))
				$this->_data[$name] = $this->_dataAccessor->getField($name);
			return $this->_data[$name];
		}
		return $this->$name;
	}


	protected function dataSet($name, $value) {
		if (isset($this->_dataFields[$name])) {
			$this->_data[$name] = $value;
			$this->_dataAccessor->setField($name, $value);
		}
		else
			$this->$name = $value;
		return $this;
	}

}
