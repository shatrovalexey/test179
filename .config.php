<?php
return [
    'request' => [
        'opts' => [
            \CURLOPT_TIMEOUT => 60
            , \CURLOPT_CUSTOMREQUEST => 'GET'
            , \CURLOPT_HEADER => false
            , \CURLOPT_RETURNTRANSFER => true
            , \CURLOPT_SSL_VERIFYHOST => false
            , \CURLOPT_SSL_VERIFYPEER => false
            , \CURLOPT_VERBOSE => true
            ,
        ]
        , 'tryes' => 10
        , 'origin' => 'https://jsonplaceholder.typicode.com'
        ,
    ]
    , 'dba' => [
        'dsn' => 'mysql:dbname=test179'
        , 'user' => 'root'
        , 'passwd' => 'f2ox9erm'
        , 'charset' => 'utf8mb4'
        , 'query' => [
            'search' => [
                'list' => '
SELECT
    `p1`.*
FROM
    `post` AS `p1`;
                '
                , 'find' => '
SELECT
    `p1`.`title`
    , `c1`.`body`
FROM
    `post` AS `p1`

        INNER JOIN `comment` AS `c1`
            ON (`p1`.`id` = `c1`.`postId`)
    WHERE
        (MATCH(`c1`.`body`) AGAINST(:fts IN BOOLEAN MODE))
;
                '
            ]
            , 'results' => [
                'comment' => '
SELECT
    count(*) AS `count`
FROM
    `comment` AS `c1`
WHERE
    (`c1`.`sessionId` = :sessionId);
                '
                , 'post' => '

SELECT
    count(*) AS `count`
FROM
    `post` AS `p1`
WHERE
    (`p1`.`sessionId` = :sessionId);
                '
                ,
            ]
            , 'after' => [
                    '
DELETE
    `c1`.*
FROM
    `comment` AS `c1`
WHERE
    (`c1`.`sessionId` <> :sessionId);
                    '
                    , '
DELETE
    `p1`.*
FROM
    `post` AS `p1`
WHERE
    (`p1`.`sessionId` <> :sessionId);
                '
                ,
            ]
            , 'before' => [
                [
                        '
REPLACE INTO
    `post`
SET
    `id` := :id
    , `userId` := :userId
    , `title` := :title
    , `body` := :body
    , `sessionId` := :sessionId;
                    '
                    , '/posts'
                    , [
                        ':id' => 'id'
                        , ':userId' => 'userId'
                        , ':title' => 'title'
                        , ':body' => 'body'
                        ,
                    ]
                    ,
                ]
                , [
                    '
REPLACE INTO
    `comment`
SET
    `id` := :id
    , `postId` := :postId
    , `name` := :name
    , `email` := :email
    , `body` := :body
    , `sessionId` := :sessionId;
                    '
                    , '/comments'
                    , [
                        ':id' => 'id'
                        , ':postId' => 'postId'
                        , ':name' => 'name'
                        , ':email' => 'email'
                        , ':body' => 'body'
                        ,
                    ]
                    ,
                ]
                ,
            ]
            ,
        ]
        ,
    ]
    ,
];