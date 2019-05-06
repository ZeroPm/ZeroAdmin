<?php
namespace api\modules\v1\controllers;

use yii;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use yii\data\ArrayDataProvider;
use api\models\Wechat;
set_time_limit(0);//最长运行时间不限制。
ini_set ('memory_limit', '256M');//内存大小设置到256m

class TemplateController extends ActiveController
{	

    public $modelClass = 'common\models\UserRank';

    public function behaviors() {
        return ArrayHelper::merge (parent::behaviors(), [ 
                'authenticator' => [ 
                    'class' => QueryParamAuth::className(),
                    // 'optional' => [
                       
                    // ],
                ] 
        ] );
    }

    //微信模板消息发送
    public function actionSend()
    {
        $model = new wechat();
        $data = array();
        $inFromData = $model->inFormData();
        $inFromData['user_total'] = 0;
        foreach ($inFromData as $key1 => $value) {
            if($value['user_count']>0){
                $model->upInformStatus($value['id'],1,$value['user_count']);
                $inFromData[$key1]['template_data'] = $model->templateData($value['inform_doc']);
                foreach ($value['user'] as $userValue) {

                    $inFromData[$key1]['template_data']['touser'] = $userValue['wopenid'];
                    Yii::$app->wechat->WeChatTemplate()->send($inFromData[$key1]['template_data']);
                }
                $model->upInformStatus($value['id'],2,$value['user_count']);
                $inFromData['user_total'] += $value['user_count'];
                $inFromData['user_detail'][$value['content']['province_id'].$value['id']] = $value['user_count'];
            }
        }
        return ['code'=>200,"msg"=>'成功',"通知用户数"=>$inFromData['user_total'],"通知用户明细"=>$inFromData['user_detail']];
    }
}
