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
$f1 = new NiceORM\EntityFactory(array('element' => 'Test\\Element', 'project' => 'Test\\Project', 'relation' => 'Test\\Relation'));
$f3 = new NiceORM\ActiveRowAccessorFactory($mappers);
$manager = new NiceORM\Manager($f1, $f2 = new NiceORM\TableCollectionFactory, $f3, $mappers);
$f2->injectManager($manager);
$mappers->inject($db, $manager);

$mapper = $mappers->get(NULL);
$entity = $mapper->get(1);
Assert::same( 'Test\Element', get_class($entity) );
Assert::same('Class 1', $entity->name);


$project = $entity->project;
Assert::same( 'Test\Project', get_class($project) );
Assert::same(1, $project->id);
Assert::same($project, $entity->project);


$relations = $entity->starts;
Assert::same( 'NiceORM\TableCollection', get_class($relations) );
Assert::same( 1, count($relations) );
foreach ($relations as $r)
	Assert::same( 'Test\Relation', get_class($r) );


$relations = $entity->ends;
Assert::same( 'NiceORM\TableCollection', get_class($relations) );
Assert::same( 2, count($relations) );
foreach ($relations as $r)
	Assert::same( 'Test\Relation', get_class($r) );


$table = $mapper->getAll();
Assert::same( 'NiceORM\TableCollection', get_class($table) );
Assert::same(6, count($table));


$entity->name = 'New Class';
$mapper->save($entity);
Assert::same('New Class', $mapper->get(1)->name);


$mapper->delete($entity);
Assert::null($mapper->get(1));


$entity = new Element;
$entity->name = 'Dolor sit amet';
$entity->project = $project;
Assert::true( $entity->project instanceof Project );
$modified = $entity->getDataModified();
Assert::true( $modified['project'] instanceof Project );
Assert::true( $modified['project'] instanceof NiceORM\Entity );

$mapper->save($entity);
Assert::same(7, $entity->id);
Assert::same($entity->project, $project);



