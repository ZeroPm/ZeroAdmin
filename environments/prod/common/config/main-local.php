<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yiicms',
            'username' => 'xiaobai',
            'password' => 'aHVhbmdjaGVuMjAxOA==',
            'charset' => 'utf8',
            'tablePrefix' => 't_',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        //短信插件
        'sms' => [
            'class' => 'common\components\sms'
        ],
        //自定义生成昵称
        'nickname' => [
            'class' => 'common\components\nickname'
        ],
        //自定义ip地址获取物理地址
        'ipaddress' => [
            'class' => 'common\components\ipaddress'
        ],
        //腾讯地图API组件
        'map' => [
            'class' => 'common\components\map'
        ],
        //微信组件
        'wechat' => [
            'class' => 'common\components\wechat',
            'config' => [
                'token' => '329134317cf0f7db',
                'appid' => 'wxd787acbc2cd96f1f',
                'appsecret' => '0fd275164ace6bb9226ddce724de3872',
                'encodingaeskey' => 'YMI9lVV66PPVwPpUPMwrP2zVWB9NjK8nVCXpCD4hFmB',
                'miniappid' => 'wx8421f195ef6f0716',
                'miniappsecret' => '128af21b80a0f23d4ea10958c22b80d4',
                'mch_id' => 'aaa',
                'mch_key' => 'aaa',
                'ssl_cer' => 'aaa',
                'ssl_key' => 'aaa',
                'cache_path' => '',
            ],
        ],
        
    ],
];
