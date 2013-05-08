<?php

namespace Test;

use NiceORM,
	Nette,
	Tester,
	Tester\Assert;

require __DIR__ . '/bootstrap.php';

$db = include 'connect.inc.php';

class MapperAccessor implements NiceORM\IMapperAccessor
{
	protected $instance;
	protected $db;
	protected $manager;

	public function inject($db, $manager) {
		$this->db = $db;
		$this->manager = $manager;
	}

	public function get($type) {
		if ($this->instance === NULL) {
			$fields = array('id' => 'id', 'name' => 'name', 'type' => 'type');
			$refs = array('project' => array('core_project', 'project_id', 'project'));
			$related = array('starts' => array('core_relation.start_id', NULL, 'relation'), 'ends' => array('core_relation', 'end_id', 'relation'));

			$this->instance = new NiceORM\ActiveRowMapper('element', 'core_element', $fields, $refs, $related, $this->db, $this->manager);
		}
		return $this->instance;
	}
}


$mappers = new MapperAccessor;
$f3 = new NiceORM\ActiveRowAccessorFactory($mappers);
$manager = new NiceORM\Manager($f1 = new FooFactory, $f2 = new ArrayIteratorFactory, $f3, $mappers);
$mappers->inject($db, $manager);

$mapper = $mappers->get(NULL);
$entity = $mapper->get(1);
Assert::same( 'Test\FooEntity', get_class($entity) );
Assert::same('Class 1', $entity->name);


$table = $mapper->getAll();
Assert::true( $table instanceof Nette\Database\Table\Selection );
Assert::same(6, count($table));



