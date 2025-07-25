<?php
namespace Alexe\Test179;

header('Content-Type: application/json');

require_once('../vendor/autoload.php');
$config = require_once('../.config.php');

$query = Codec::getFts($_REQUEST['q'] ?? null);
$dba = &$config['dba'];
$search = &$dba['query']['search'];
$args = [$search[$query ? 'find' : 'list'],];

if ($query) $args[] = [':fts' => $query,];

echo Codec::getEncodeJson((new DBA($dba))->query(... $args)->fetchAll(\PDO::FETCH_ASSOC));