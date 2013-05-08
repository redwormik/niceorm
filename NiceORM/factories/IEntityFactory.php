<?php

namespace NiceORM;


interface IEntityFactory
{


	/** @return Entity */
	function create($type, $data);

}
