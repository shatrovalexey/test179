<?php
namespace Alexe\Test179;

require_once('vendor/autoload.php');
$config = require_once('.config.php');

$argsDefault = [':sessionId' => Codec::getHash(time()),];

$req = new Request($config['request']);
$dba = new DBA($config['dba']);
$dbh = $dba->dbh();

foreach ($config['dba']['query']['before'] as [$sql, $url, $fields,]) {
    if (!($data = $req->getData($url))) continue;

    $keys = array_keys($fields);
    $sth = $dbh->prepare($sql);

    foreach ($data as $i => $item)
        $sth->execute(
            array_combine(
                array_keys($fields)
                , array_map(fn (string $key): string => $item[$key] ?? null, array_values($fields))
            ) + $argsDefault
        );

    $sth->closeCursor();
}

/**
foreach ($config['dba']['query']['after'] as $sql)
    $dba->query($sql, $argsDefault)->closeCursor();
*/

$results = [];

foreach ($config['dba']['query']['results'] as $key => $sql) {
    $results[$key] = $dba->query($sql, $argsDefault)->fetchColumn();
}

echo "Загружено {$results['post']} записей и {$results['comment']} комментариев" . \PHP_EOL;