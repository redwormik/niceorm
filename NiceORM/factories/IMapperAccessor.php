<?php

namespace NiceORM;


interface IMapperAccessor
{


	/** @return IMapper */
	function get($type);

}
