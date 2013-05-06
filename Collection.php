<?php

namespace NiceORM;

use Nette,
	Nette\Database\Table;


class Collection extends Nette\FreezableObject implements \Iterator, \Countable
{

	protected $manager;
	protected $type;
	protected $selection;
	protected $data;


	public function __construct(Manager $manager, $type, Table\Selection $selection) {
		$this->manager = $manager;
		$this->type = $type;
		$this->selection = $selection;
	}


	/********* selection *********/


	public function where($field, $value)
	{
		$this->updating();
		// do some mapping here
		$this->selection->where($field, $value);
		return $this;
	}


	public function order($columns)
	{
		$this->updating();
		// do some mapping here
		$this->selection->order($columns);
		return $this;
	}


	public function limit($limit, $offset = NULL)
	{
		$this->updating();
		$this->selection->limit($limit, $offset);
		return $this;
	}


	public function page($page, $itemsPerPage)
	{
		$this->updating();
		$this->selection->page($page, $itemsPerPage);
		return $this;
	}


	public function group($columns, $having = '')
	{
		$this->updating();
		// do some mapping here
		$this->selection->group($columns, $having);
		return $this;
	}


	/********* aggregation *********/


	public function collect($item, $preserveKeys = FALSE) {
		$result = array();

		if (is_array($item) || $item instanceof \Closure || $item instanceof Nette\Callback)
			$cb = \callback($item);
		else $cb = FALSE;

		foreach ($this as $key => $row) {
			$value = $cb ? $cb($row) : $row->$item;
			if ($preserveKeys)
				$result[$key] = $value;
			else
				$result[] = $value;
		}

		return $result;
	}


	public function aggregation($function)
	{
		// do some mapping here
		return $this->selection->aggregation($function);
	}


	public function min($column)
	{
		// do some mapping here
		return $this->selection->min($column);
	}


	public function max($column)
	{
		// do some mapping here
		return $this->selection->max($column);
	}


	public function count($column = '')
	{
		// do some mapping here
		return $this->selection->count($column);
	}


	public function sum($column)
	{
		// do some mapping here
		return $this->selection->sum($column);
	}


	/********* interface Iterator *********/


	public function rewind()
	{
		$this->loadData();
		reset($this->data);
	}


	public function current()
	{
		return current($this->data);
	}


	public function key()
	{
		return key($this->data);
	}


	public function next()
	{
		return next($this->data);
	}


	public function valid()
	{
		return current($this->data) !== FALSE;
	}


	/*********  *********/


	protected function loadData()
	{
		if ($this->isFrozen())
			return;
		$this->freeze();

		$this->data = array();
		foreach ($this->selection as $key => $row)
			$this->data[$key] = $this->manager->createEntity($this->type, $row);
	}


}
