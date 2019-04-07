<?php

namespace api\modules\v1\controllers;

use yii;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use api\models\LoginCuser;
use api\models\GetProvince;

class WechatController extends ActiveController
{	

    public $modelClass = 'common\models\UserRank';

    public function behaviors() {
        return ArrayHelper::merge (parent::behaviors(), [ 
                'authenticator' => [ 
                    'class' => QueryParamAuth::className(),
                    'optional' => [
						'login',
						'content',
						'map',
					],
                ] 
        ] );
    }
    //获取用户及操作信息
    public function actionLogin ()
	{
		$data = Yii::$app->request->post();

		if($data){

			$weminidata = Yii::$app->wechat->WeMiniCrypt()->session($data['code']);
			
			if(array_key_exists("errcode",$weminidata)){

				return ['code'=>400,"msg"=>'登录失败',"data"=>$weminidata];

			}else{
		
				$model = new LoginCuser();

				$model->setAttributes($weminidata);

				$user = $model->getUser($weminidata);

				$userdata = $model->getUserData($user->id);

				$userdata['openid'] = $user->mopenid;

				$userdata['isfollow'] = $user->isfollow;

				$userdata['unionid'] = $user->union_id;

		        return ['code'=>200,"msg"=>'登录成功',"data"=>$userdata];
			}

		}else{

			return ['code'=>400,"msg"=>"未接收到code"];

		}
        
	}

	//获取省份及内容
	public function actionContent()
	{
		$data = Yii::$app->request->post();

		if($data){
			//$data['identity'] = [1,2,3];
			$model = new GetProvince();
			$model->setAttributes($data);
			return ['code'=>200,"msg"=>'数据接收成功',"data"=>$model->getContent()];
		}else{
			return ['code'=>400,"msg"=>"未接收到数据"];
		}
	}

	//用户首次进入逆地址解析并返回省份及内容
	public function actionMap($location)
	{
		$model = new GetProvince();

		$mapApi = Yii::$app->map->getmap($location);

		if($mapApi->result->ad_info->nation_code==156&&$mapApi->status==0){
			$province = $model->getProvince($mapApi->result->ad_info->province);

			return ['code'=>200,"msg"=>'解析成功',"data"=>["province_id"=>$province->province_id,"name"=>$province->name]];
			//return $mapApi->status ? ['code'=>$mapApi->status,"msg"=>$mapApi->message] : ['code'=>400,"msg"=>'暂时只支持中国的行政区',"data"=>$mapApi->result->address_component->nation];

		}else{
			return $mapApi->status ? ['code'=>$mapApi->status,"msg"=>$mapApi->message] : ['code'=>400,"msg"=>'暂时只支持中国的行政区',"data"=>$mapApi->result->address_component->nation];
		}
	}

	//用户操作记录
	public function actionAddop()
	{
		$data = Yii::$app->request->post();

		if($data){

			$model = new GetProvince();

			$userdata = $model->getUserData($data['openid']);

			$operation = $model->addOperation($userdata->id,$data['type'],$data['province_id'],$data['isub']);

			if($operation){
				$operations['province_id'] = $operation->province_id;
				$operations['type'] = $operation->type;
				$operations['created_at'] = $operation->created_at;
				$operations['isub'] = $operation->isub;
				return ['code'=>200,"msg"=>'操作写入成功',"data"=>$operations];
			}else{
				return ['code'=>400,"msg"=>'操作写入失败'];
			}
		}else{
			return ['code'=>400,"msg"=>'未接收到数据'];
		}
	}

}
