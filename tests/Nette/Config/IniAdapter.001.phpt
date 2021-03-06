<?php

/**
 * Test: Nette\Config\IniAdapter
 *
 * @author     David Grudl
 * @package    Nette\Config
 * @subpackage UnitTests
 */

use Nette\Config\Config;



require __DIR__ . '/../bootstrap.php';

define('TEMP_FILE', TEMP_DIR . '/cfg.ini');


// Load INI
$config = Config::fromFile('config.ini');
Assert::equal( array(
	'production' => array(
		'webname' => 'the example',
		'database' => array(
			'params' => array(
				'host' => 'db.example.com',
				'username' => 'dbuser',
				'password' => 'secret',
				'dbname' => 'dbname',
			),
			'adapter' => 'pdo_mysql',
		),
	),
	'development' => array(
		'database' => array(
			'params' => array(
				'host' => 'dev.example.com',
				'username' => 'devuser',
				'password' => 'devsecret',
				'dbname' => 'dbname',
			),
			'adapter' => 'pdo_mysql',
		),
		'set' => array(
			'date.timezone' => 'Europe/Prague',
		),
		'timeout' => '10',
		'display_errors' => '1',
		'html_errors' => '',
		'items' => array(
			'10',
			'20',
		),
		'webname' => 'the example',
	),
), $config );



// Save INI
Config::save($config, TEMP_FILE);
Assert::match( <<<EOD
; generated by Nette

[production]
webname = "the example"
database.params.host = "db.example.com"
database.params.username = "dbuser"
database.params.password = "secret"
database.params.dbname = "dbname"
database.adapter = "pdo_mysql"

[development]
database.params.host = "dev.example.com"
database.params.username = "devuser"
database.params.password = "devsecret"
database.params.dbname = "dbname"
database.adapter = "pdo_mysql"
timeout = 10
display_errors = 1
html_errors = ""
items.0 = 10
items.1 = 20
webname = "the example"
set.date.timezone = "Europe/Prague"
EOD
, file_get_contents(TEMP_FILE) );


// Section
$config = Config::fromFile('config.ini', 'development');
Assert::equal( array(
	'database' => array(
		'params' => array(
			'host' => 'dev.example.com',
			'username' => 'devuser',
			'password' => 'devsecret',
			'dbname' => 'dbname',
		),
		'adapter' => 'pdo_mysql',
	),
	'set' => array(
		'date.timezone' => 'Europe/Prague',
	),
	'timeout' => '10',
	'display_errors' => '1',
	'html_errors' => '',
	'items' => array(
		'0' => '10',
		'1' => '20',
	),
	'webname' => 'the example',
), $config );
