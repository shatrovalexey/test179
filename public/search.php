<?php
namespace Alexe\Test179;

header('Content-Type: application/json');

require_once('../vendor/autoload.php');
$config = require_once('../.config.php');

echo Codec::getEncodeJson(
    (new DBA($config['dba']))
        ->query(
            ... (
                empty($_REQUEST['q'])
                    ? [$config['dba']['query']['search']['list'],]
                    : [$config['dba']['query']['search']['find'], [':fts' => Codec::getFts($_REQUEST['q']),],]
            )
        )
        ->fetchAll(\PDO::FETCH_ASSOC)
);