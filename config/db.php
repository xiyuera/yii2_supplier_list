<?php

define('DB_PATH', __DIR__ . '/../database/');

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'sqlite:' . DB_PATH . '/test.sqlite',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
