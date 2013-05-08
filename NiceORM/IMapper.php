<?php

namespace NiceORM;


interface IMapper
{

	/** @return Entity */
	function get($id);


	/** @return ICollection */
	function getAll();


	function save(Entity $entity);


	function delete(Entity $entity);

}
