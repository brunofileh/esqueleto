<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=localhost;dbname=sisscm', 
    'username' => 'postgres',
    'password' => 'postgres',
    'charset' => 'utf8',
    
    'schemaMap' => [
        'pgsql'=> [
            'class' => 'yii\db\pgsql\Schema',
            'defaultSchema' => 'public'
        ]
    ],

    // habilitar caching do esquema em produção
    'enableSchemaCache' => $params['habilitar-cache-global'],
    // Duração do caching
    'schemaCacheDuration' => 3600,
    // Nome do componente usado para armazenamento
    'schemaCache' => 'cache',
];
