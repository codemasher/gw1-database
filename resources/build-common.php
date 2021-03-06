<?php
/**
 * @filesource   build-common.php
 * @created      14.04.2018
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GW1DBBuild;

use chillerlan\Database\{
	Database, DatabaseOptionsTrait, Drivers\MySQLiDrv
};
use chillerlan\HTTP\{Psr18\CurlClient, HTTPOptionsTrait, Psr17\RequestFactory};
use chillerlan\Settings\SettingsContainerAbstract;
use chillerlan\SimpleCache\MemoryCache;
use chillerlan\DotEnv\DotEnv;
use Psr\Log\AbstractLogger;

mb_internal_encoding('UTF-8');

const DIR_CFG       = __DIR__.'/../config';
const DIR_JSON      = __DIR__.'/../public/gwdb/json';
const DIR_IMG       = __DIR__.'/../public/gwdb/img';
const SKILLIMG_ORIG = __DIR__.'/img/skills/original';

const LANGUAGES     = ['de', 'en'];//, 'fr'

require_once __DIR__.'/../vendor/autoload.php';

$env = (new DotEnv(DIR_CFG, '.env', false))->load();

$o = [
	// DatabaseOptions
	'driver'      => MySQLiDrv::class,
	'host'        => $env->DB_HOST,
	'port'        => $env->DB_PORT,
	'socket'      => $env->DB_SOCKET,
	'database'    => $env->DB_DATABASE,
	'username'    => $env->DB_USERNAME,
	'password'    => $env->DB_PASSWORD,
	// RequestOptions
	'ca_info'     => DIR_CFG.'/cacert.pem',
	'user_agent'  => 'GW1DB/1.0.0 +https://github.com/codemasher/gw1-database',
];

$options = new class($o) extends SettingsContainerAbstract{
	use DatabaseOptionsTrait, HTTPOptionsTrait;

};

$logger = new class() extends AbstractLogger{
	public function log($level, $message, array $context = []){
		echo sprintf('[%s][%s] %s', date('Y-m-d H:i:s'), substr($level, 0, 4), trim($message))."\n";
	}
};

$http  = new CurlClient($options);
$cache = new MemoryCache;
$db    = new Database($options, $cache, $logger);
$rf    = new RequestFactory;

$db->connect();
