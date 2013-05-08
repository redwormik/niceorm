<?php

$db = new Nette\Database\Connection('mysql:host=127.0.0.1', 'root');
Nette\Database\Helpers::loadFromFile($db, __DIR__ . '/database.sql');
return $db;
