<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\redis\Cache',
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'task/view/<id:\d+>' => 'tasks/view',
                'tasks/page/<page:\d+>' => 'tasks/view',
                'user/view/<id:\d+>' => 'users/view',
                'users/page/<page:\d+>' => 'users/view',
                    [
                        'class' => 'yii\rest\UrlRule', 
                        'controller' => 'api/messages', 
                        'extraPatterns' => [
                            'GET /' => 'new',
                        ]
                    ]
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '7896681',
                    'clientSecret' => 'w4H7malWwPbUbzJqoLjG',
                ],
                // и т.д.
            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
    ],
];
