<?php

// Doctrine (db)
$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
    'host'     => 'blogwriter.dev',
    'port'     => '3306',
    'dbname'   => 'blogwriter',
    'user'     => 'blogwriter_user',
    'password' => 'secret',
);

$app['monolog.level'] = 'WARNING';