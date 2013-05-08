<?php

namespace NiceORM;

use Nette,
	Nette\Utils\Arrays;


class NiceORMExtension extends Nette\Config\CompilerExtension
{

	public $defaults = array(
		'tables' => array(),
		'fields' => array(),
		'relations' => array(),
		'entities' => array(),
	);

	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig();

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



		$mappers = array();
		foreach ($config['tables'] as $domain => $table) {
			$container->addDefinition($mappers[$domain] = $this->prefix($domain.'.mapper'))
				->setClass('NiceORM\\IMapper')
				->setFactory('NiceORM\\ActiveRowMapper', array($domain, $table, $fields[$domain], $refs[$domain], $related[$domain]));
		}

		$mapperAccessor = $container->addDefinition($this->prefix('mapperAccessor'))
			->setClass('NiceORM\\IMapperAccessor')
			->setFactory('NiceORM\\DIMapperAccessor', array($mappers) );

		$accessorFactory = $container->addDefinition($this->prefix('accessorFactory'))
			->setClass('NiceORM\\IAccessorFactory')
			->setFactory('NiceORM\\ActiveRowAccessorFactory', array($mapperAccessor) );


		$entities = $collections = array();
		foreach ($config['entity'] as $domain => $entity) {
			$container->addDefinition($entities[$domain] = $this->prefix($domain.'.entity'))
				->setClass($entity)
				->setShared(FALSE);

			$container->addDefinition($collections[$domain] = $this->prefix($domain.'.collection'))
				->setClass('NiceORM\\TableCollection')
				->setFactory('NiceORM\\TableCollection', array($domain, '%data%'))
				->setParameters(array('data'))
				->setShared(FALSE);
		}

		$entityFactory = $container->addDefinition($this->prefix('entityFactory'))
			->setClass('NiceORM\\IEntityFactory')
			->setFactory('NiceORM\\DIEntityFactory', array($entities) );

		$collectionFactory = $container->addDefinition($this->prefix('collectionFactory'))
			->setClass('NiceORM\\ICollectionFactory')
			->setFactory('NiceORM\\DICollectionFactory', array($collections) );


		$container->addDefinition($this->prefix('manager'))
			->setClass('NiceORM\\Manager')
			->setFactory('NiceORM\\Manager', array($entityFactory, $collectionFactory, $accessorFactory, $mapperAccessor) );
	}


}
