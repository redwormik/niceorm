<?php

namespace Test;

use NiceORM,
	Nette,
	Tester,
	Tester\Assert;

require __DIR__ . '/bootstrap.php';


$compiler = new Nette\Config\Compiler;
$compiler->addExtension( 'niceorm', new NiceORM\NiceORMExtension );

$config = Nette\Utils\Neon::decode(
<<<'EOT'
services:
	database: Nette\Database\Connection('')
niceorm:
	tables:
		diagram: core_diagram
		element: core_element
		placement: core_placement
		project: core_project
		relation: core_relation
	fields:
		diagram: [ id, name, type ]
		element: [ id, name, type ]
		placement: [ posX, posY ]
		project: [ id, name ]
		relation: [ id, name, type ]
	relations:
		diagram: [ project: [core_project, project_id] ]
		element: [ project: project_id ]
		placement: [ diagram, element ]
		relation: [ start: [core_element, start_id, element], end: [core_element, end_id, element] ]
	entity:
		diagram: Test\Diagram
		element: Test\Element
		placement: Test\Placement
		project: Test\Project
		relation: Test\Relation
EOT
);

$php = $compiler->compile($config, 'SystemContainer', 'Nette\DI\Container');
@mkdir(__DIR__ . '/output');
$fh = fopen( __DIR__ . '/output/container.php', 'w' );
fwrite($fh, $php);
fclose($fh);

