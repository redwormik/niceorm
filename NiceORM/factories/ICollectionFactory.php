<?php

namespace NiceORM;


interface ICollectionFactory
{


	/** @return ICollection */
	function create($type, $data);

}
