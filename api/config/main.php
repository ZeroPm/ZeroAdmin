<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        "v1" => [        
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
			'enableSession' => false,
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
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'enableStrictParsing' => true,
			'rules' => [
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => ['v1/article'],
					'pluralize'=>false,
                    'extraPatterns' => [
                        'POST addan' => 'addan',
                    ]
				],
                //微信小程序的API
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/wechat'],
                    'pluralize'=>false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST content' => 'content',
                        'GET map' => 'map',
                        'POST addop' => 'addop',
                        'GET item' => 'item',
                        'POST decrypt' => 'decrypt',
                        'POST upuser' => 'upuser',
                        'GET getann' => 'getann',
                        'POST read' => 'read',
                        'POST reads' => 'reads',
                        'GET config' => 'config',
                    ]
                ],
                //微信公众号API
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/wechats'],
                    'pluralize'=>false,
                    'extraPatterns' => [
                        'GET receive' => 'receive',
                        'POST receive' => 'receive',
                        // 'POST qrcode' => 'qrcode',
                        // 'GET menu' => 'menu',
                        //'GET userinfo' => 'userinfo',
                    ]
                ],
                //公众号模板消息API
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/template'],
                    'pluralize'=>false,
                    'extraPatterns' => [
                        'GET send' => 'send',
                    ]
                ],
                //登录API模板获取access_token
				[
					'class' => 'yii\rest\UrlRule',
					'controller' => ['v1/user'],
					'pluralize'=>false,
                    'extraPatterns' => [
                        'POST login' => 'login',
                    ]
				]
			]
		],
    ],
    'params' => $params,
];
