<?php
namespace Alexe\Test179;

header('Content-Type: application/json');

require_once('../vendor/autoload.php');
$config = require_once('../.config.php');

$query = Codec::getFts($_REQUEST['q'] ?? null);

echo Codec::getEncodeJson(
    (new DBA($config['dba']))->query(
        $config['dba']['query']['search'][$query ? 'list' : 'find']
            , $query ? [':fts' => $query,] : []
    )->fetchAll(\PDO::FETCH_ASSOC)
);