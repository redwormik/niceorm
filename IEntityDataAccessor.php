<?php

namespace NiceORM;


interface IEntityDataAcessor
{

	/** @return mixed */
	function & getField($name);


	/** @return array() */
	function getFieldNames();

}
