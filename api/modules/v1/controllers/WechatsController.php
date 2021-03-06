<?php

namespace api\modules\v1\controllers;

use yii;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use yii\data\ArrayDataProvider;
use api\models\Wechat;


class WechatsController extends ActiveController
{	

    public $modelClass = 'common\models\UserRank';

    public function behaviors() {
        return ArrayHelper::merge (parent::behaviors(), [ 
                'authenticator' => [ 
                    'class' => QueryParamAuth::className(),
                    'optional' => [
						'receive',
                        //'memu'
					],
                ] 
        ] );
    }

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
    //接收微信信息自动回复
    public function actionReceive()
    {

        $model = Yii::$app->wechat->WeChatReceive();

        $msgType = $model->getMsgType();

        $wechat = new Wechat();

        if($msgType=='event'){

            $formContent = $model->getReceive('Event');
            switch ($formContent) {
                case "subscribe":
                    $data = $wechat->getUser($model->getOpenid());
                    switch ($data){
                        case 1:
                            $content = "感谢关注~小白提示：订阅自考信息成功，届时会在对应时间通知各位.";
                        break;
                        case 2:
                            $content = "感谢关注~小白提示：你已经订阅成功~";
                        break;
                        case 3:
                            $content = '感谢关注~小白暂未发现你有订阅自考信息.进入<a data-miniprogram-appid="wx8421f195ef6f0716" data-miniprogram-path="pages/userCenter/userCenter">小程序订阅~</a>.';
                        break;
                        default:
                            $content = '感谢关注~小白提示：订阅自考信息失败，请重新订阅绑定~Errorcode:'.$data.'.';
                    }
                    $model->text($content);
                    break;
                case "unsubscribe":
                    $wechat->unsubScribe($model->getOpenid());
                    echo 'success';
                    exit();
                    break;
                case "CLICK":
                    $key_value = $model->getReceive('EventKey');
                    switch ($key_value) {
                        case 'SUB_FOLLOW':
                            $data = $wechat->getUser($model->getOpenid());
                            switch ($data){
                                case 1:
                                    $content = "小白提示：订阅自考信息成功，届时会在对应时间通知各位.";
                                break;
                                case 2:
                                    $content = "小白提示：你已经订阅成功~";
                                break;
                                case 3:
                                    $content = '小白暂未发现你有订阅自考信息.进入<a data-miniprogram-appid="wx8421f195ef6f0716" data-miniprogram-path="pages/userCenter/userCenter">小程序订阅~</a>.';
                                break;
                                default:
                                    $content = '小白提示：订阅自考信息失败，请重新订阅绑定~Errorcode:'.$data.'.';
                            }
                            $model->text($content);
                            break;
                        
                        case 'CONTACT_XIAOBAI':
                            $model->image('0-q45ex5MHR_RXJ4lKXIE-FYZiLj5l8vMshZxfsxWQY');
                            break;
                    }
                    //$content = $formContent.$key_value;
                    break;
                default:
                    echo 'success';
                    exit();
            }
            
            $model->reply();
        }else if($_SERVER['REQUEST_METHOD'] == "GET"){
            $model->reply();
            exit();
        }else{
            //$model->text('小白白，白又白，两只耳朵竖起来~~~');
            //$model->reply();
            echo 'success';
            exit();
        }
    }

    //生成微信公众号二维码
    public function actionQrcode()
    {  
        //$token = Yii::$app->wechat->WeChatQrcode()->getAccessToken();


        //$code = Yii::$app->wechat->WeChatQrcode()->create(1);

        //return ['code'=>$code,"msg"=>'成功'];
        //return Yii::$app->wechat->WeChatQrcode()->url();
    }

    //更新微信公众号菜单
    public function actionMenu()
    {

        $data = array('button'=>array(
            array(
                'name'=>'进入小程序',
                'type'=>'miniprogram',
                'appid'=>'wx8421f195ef6f0716',
                'url'=>'http://mp.weixin.qq.com',
                'pagepath'=>'pages/index/index',
            ),
            array(
                'name'=>'订阅绑定',
                'type'=>'click',
                'key'=> 'SUB_FOLLOW',
            ),
            array(
                'name'=>'联系小白',
                'type'=>'click',
                'key'=> 'CONTACT_XIAOBAI',
            ),
        ));

        //$data = json_encode($data);

        $a = Yii::$app->wechat->WeChatMenu()->create($data);

        return $a;
        $data = Yii::$app->wechat->WeChatMedia()->batchGetMaterial();
        return $data;
    }

    public function actionUserinfo()
    {
        $model = new Wechat();
        $data = $model->getUser('org9b04U2wugyy_GaMkXf-94-4bY');
        return ['code'=>200,"msg"=>'成功','data'=>$data];
    }

}
