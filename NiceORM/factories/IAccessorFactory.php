<?php

namespace NiceORM;


interface IAccessorFactory
{


	/** @return IFieldAccessor */
	function create($type, $data);

}
