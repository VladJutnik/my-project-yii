<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=custom-yii2',
            'username' => 'root',
            'password' => 'Lampa6516',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => '1@niig.su',
                'password' => 'Lampa6516',
                'port' => 465,
                'encryption' => 'ssl',
            ],
            'useFileTransport' => false,
        ],
    ],
];
