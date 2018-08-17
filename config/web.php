<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$config = [
    'id' => 'basic',
    'name' => 'Wiki',
   'defaultRoute' => 'personal/favorites',
    'language' => 'ru-RU',
	'defaultRoute' => 'personal/favorites',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
       // '@staticRoot' => $params['staticPath'],
       // '@static'   => $params['staticHostInfo'],
    ],
    'modules' => [        
	'attachments' => [
            'class' => nemmo\attachments\Module::className(),
            'tempPath' => '@app/web/uploads/post/temp',
            'storePath' => '@app/web/uploads/post/store',
            'rules' => [ // Rules according to the FileValidator
               // 'maxFiles' => 20, // Allow to upload maximum 3 files, default to 3
                //'mimeTypes' => 'image/png', // Only png images
                'maxSize' => 30*1024 * 1024 // 30 MB
            ],
            'tableName' => '{{%attachments}}' // Optional, default to 'attach_file'
        ],
        'treemanager' =>  [
            'class' => '\kartik\tree\Module',
            'treeViewSettings'=> [
                'nodeView' => '@app/views/category/_form'
            ]
            // other module settings, refer detailed documentation
        ]
    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\Controller',
            'access' => ['@'], //глобальный доступ к фаил менеджеру @ - для авторизорованных , ? - для гостей , чтоб открыть всем ['@', '?']
            'disabledCommands' => ['netmount'], //отключение ненужных команд https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#commands
            'roots' => [
                [
                    'class' => 'mihaildev\elfinder\volume\UserPath',
                    'path'  => '/uploads/common',
                    'name'  => 'Общие'
                ],
                [
                    'class' => 'mihaildev\elfinder\volume\UserPath',
                    'path'  => '/uploads/user_{id}',
                    'name'  => 'My Documents'
                ],
               /* [
                    'path' => 'files/some',
                    'name' => ['category' => 'my','message' => 'Some Name'] //перевод Yii::t($category, $message)
                ],
                [
                    'path'   => 'files/some',
                    'name'   => ['category' => 'my','message' => 'Some Name'], // Yii::t($category, $message)
                    'access' => ['read' => '*', 'write' => 'UserFilesAccess'] // * - для всех, иначе проверка доступа в даааном примере все могут видет а редактировать могут пользователи только с правами UserFilesAccess
                ]*/
            ],
        ]
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache' //Включаем кеширование
        ],
        'request' => [
		//'baseUrl' => '',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'loNxU1vGezoiSfGpyOtj0RXSzRexxLQt',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => [ 'auth/login' ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

       /* 'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
              '' => 'site/index',

                'login' => 'auth/login',
                'logout' => 'auth/logout',
                'signup' => 'auth/signup',

                '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
                '<_c:[\w\-]+>' => '<_c>/index',
                '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
                '<_c:[\w\-]+>/<_a:[\w\-]+>/<url:[\w\-]+>' => '<_c>/<_a>',

            ],
        ],*/
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
