<?php

namespace NiceORM;


interface IFieldAccessor
{


	/** @return mixed */
	function getField($name);


	/** @return array() */
	function getFieldNames();

}
