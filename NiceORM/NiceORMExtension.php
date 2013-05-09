<?php

namespace NiceORM;

use Nette,
	Nette\Config\Compiler,
	Nette\Utils\Arrays;


class NiceORMExtension extends Nette\Config\CompilerExtension
{

	public $defaults = array(
		'tables' => array(),
		'fields' => array(),
		'relations' => array(),
		'entity' => array(),
	);

	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);


		$entities = $collections = array();
		foreach ($config['entity'] as $domain => $entity) {
			$service = $container->addDefinition($entities[$domain] = $this->prefix($domain.'.entity'));
			Compiler::parseService($service, $entity);
			$service->setClass('NiceORM\\Entity')
				->setParameters(array('data' => NULL))
				->setShared(FALSE);


			$container->addDefinition($collections[$domain] = $this->prefix($domain.'.collection'))
				->setClass('NiceORM\\ICollection')
				->setFactory('NiceORM\\TableCollection', array($domain, '%data%'))
				->setParameters(array('data'))
				->setShared(FALSE);
		}


		$fields = $refs = $related = array();
		foreach ($config['tables'] as $domain => $table) {
			$fields[$domain] = $refs[$domain] = $related[$domain] = array();

			foreach (Arrays::get($config['fields'], $domain, array()) as $name => $column)
				$fields[$domain][is_int($name) ? $column : $name] = $column;

			foreach (Arrays::get($config['relations'], $domain, array()) as $name => $column) {
				if (is_int($name))
					$refs[$domain][$column] = array($column, NULL, $column);
				else {
					$column = array_values((array) $column);
					switch (count($column)) {
						case 0:  $refs[$domain][$name] = array($name, NULL, $name); break;
						case 1:  $refs[$domain][$name] = array($name, $column[0], $name); break;
						case 2:  $refs[$domain][$name] = array($column[0], $column[1], $name); break;
						default: $refs[$domain][$name] = array($column[0], $column[1], $column[2]);
					}
				}
			}
		}


		foreach ($refs as $domain => $tableRefs) {
			foreach ($tableRefs as $name => $ref) {
				list($table, $column, $type) = $ref;
				if ($type === $name || empty($related[$type][$domain.'s']))
					$related[$type][$domain.'s'] = array($config['tables'][$domain], $column, $domain);
				elseif (!isset($refs[$domain][$type]))
					unset($related[$type][$domain.'s']);
				$related[$type][$domain.'s:'.$name] = array($config['tables'][$domain], $column, $domain);
			}
		}


		$mappers = $accessors = array();
		foreach ($config['tables'] as $domain => $table) {
			$mapper = $container->addDefinition($mappers[$domain] = $this->prefix($domain.'.mapper'))
				->setClass('NiceORM\\ActiveRowMapper', array($domain, $table, $fields[$domain], $refs[$domain], $related[$domain]));

			$container->addDefinition($accessors[$domain] = $this->prefix($domain.'.accessor'))
				->setClass('NiceORM\\ActiveRowAccessor', array('%data%', $mapper))
				->setParameters(array('data'))
				->setShared(FALSE);
		}


		$entityFactory = $container->addDefinition($this->prefix('entityFactory'))
			->setClass('NiceORM\\DIEntityFactory', array($entities) );

		$collectionFactory = $container->addDefinition($this->prefix('collectionFactory'))
			->setClass('NiceORM\\DICollectionFactory', array($collections) );

		$accessorFactory = $container->addDefinition($this->prefix('accessorFactory'))
			->setClass('NiceORM\\DIAccessorFactory', array($accessors) );

		$mapperAccessor = $container->addDefinition($this->prefix('mapperAccessor'))
			->setClass('NiceORM\\DIMapperAccessor', array($mappers) );


		$container->addDefinition($this->prefix('manager'))
			->setClass('NiceORM\\Manager')
			->setFactory('NiceORM\\Manager', array($entityFactory, $collectionFactory, $accessorFactory, $mapperAccessor) );
	}


}
