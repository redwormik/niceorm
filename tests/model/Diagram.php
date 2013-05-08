<?php

namespace Test;


use NiceORM,
	Nette;


class Diagram extends NiceORM\Entity
{

	protected $id;
	protected $name;
	protected $type;

	protected $project;
	protected $starts = array();
	protected $ends = array();


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


	public function getType()
	{
		return $this->dataGet('type');
	}


	public function setType($type)
	{
		$this->dataSet('type', (string) $type);
		return $this;
	}


	public function getProject()
	{
		return $this->dataGet('project');
	}


	public function setProject(Project $project)
	{
		return $this->dataSet('project', $project);
	}

}
