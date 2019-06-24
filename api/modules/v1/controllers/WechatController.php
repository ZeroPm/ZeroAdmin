<?php

namespace api\modules\v1\controllers;

use yii;
use yii\rest\ActiveController;
use yii\helpers\ArrayHelper;
use yii\filters\auth\QueryParamAuth;
use api\models\LoginCuser;
use api\models\GetProvince;
use yii\data\ArrayDataProvider;
use common\models\Config;

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

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    //获取用户及操作信息
    public function actionLogin ()
	{
		$data = Yii::$app->request->post();

		//$data = $_POST['code'];

		if($data){

			$weminidata = Yii::$app->wechat->WeMiniCrypt()->session($data['code']);
			
			if(array_key_exists("errcode",$weminidata)){

				return ['code'=>$weminidata['errcode'],"msg"=>'登录失败',"data"=>$weminidata];

			}else{
		
				$model = new LoginCuser();

				$model->setAttributes($weminidata);

				$user = $model->getUser($weminidata,!empty($data['parent_uuid'])?$data['parent_uuid']:'');

				$userdata = $model->getUserData($user->id);

				$userdata['openid'] = $user->mopenid;

				$userdata['isfollow'] = $user->isfollow;

				$userdata['unionid'] = $user->union_id;
				$userdata['isread'] = $user->isread;
				// $userdata['nickname'] = $user->nickname;

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
			return ['code'=>200,"msg"=>'成功',"data"=>$model->getContent(),'brand'=>$model->getBrand()];
		}else{
			return ['code'=>400,"msg"=>"未接收到参数"];
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

			$isUpdateUser = $data['type']==2?$model->updateUserSub($userdata,$data['province_id'],$data['isub']):true;

			if($operation&&$isUpdateUser){
				$operations['province_id'] = $operation->province_id;
				$operations['type'] = $operation->type;
				$operations['created_at'] = $operation->created_at;
				$operations['isub'] = $operation->isub;
				$operations['id'] = $operation->id;
				$operations['updated_at'] = $operation->updated_at;
				return ['code'=>200,"msg"=>'操作写入成功',"data"=>$operations];
			}else{
				return ['code'=>400001,"msg"=>'操作写入失败'];
			}
		}else{
			return ['code'=>400,"msg"=>'未接收到数据'];
		}
	}

	//省份数据接口
	public function actionItem()
	{
		$model = new GetProvince();
		return ['code'=>200,"msg"=>'数据获取成功',"data"=>$model->getItem()];
	}

	//解密授权用户数据，并更新用户unionid
	public function actionDecrypt()
	{
		$data = Yii::$app->request->post();

		if($data){

			$decryptData = Yii::$app->wechat->WeMiniCrypt()->userInfo($data['code'],$data['iv'],$data['encryptedData']);

			$model = new LoginCuser();

			$model->setAttributes($decryptData);

			$user = $model->getUsers($decryptData);

			$userdata = $user->union_id;

			return ['code'=>200,"msg"=>'成功','data'=>$userdata];
		}else{
			return ['code'=>400,"msg"=>'未接收到数据'];
		}		
	}

	//更新小程序用户部分信息
	public function actionUpuser()
	{
		$data = Yii::$app->request->post();

		if($data){

			$model = new LoginCuser();

			if($userdata = $model->update($data['openid'],$data['isfollow'],$data['isread'])){
				return ['code'=>200,"msg"=>'成功','isread'=>$userdata->isread,'isfollow'=>$userdata->isfollow];
			}else{
				return ['code'=>400001,"msg"=>'数据写入失败'];
			}
		}else{
			return ['code'=>400,"msg"=>'未接收到数据'];
		}		
	}

	//获取省公告
	public function actionGetann($province_id,$openid,$updated_at)
	{
		$model = new GetProvince();
		
		$items = $model->getAnn($province_id,$openid,10);

		$unRead = 0;

        foreach ($items['item'] as $key => $value) {

            if($items['item'][$key]['created_at']>=$updated_at){

            	if($items['item'][$key]['isread']){

            		$items['item'][$key]['Isread'] = 1; 
            	}else{
            		$items['item'][$key]['Isread'] = 0;
            		$unRead = 1;
            	}
            }else{
                $items['item'][$key]['Isread'] = -1;
            }
        }

		return ['code'=>200,"msg"=>'成功','data'=>$items['item'],'page'=>$items['pages']->page,'pageCount'=>$items['pages']->pageCount,'unRead'=>$unRead];
	}

	//单个点击公告已读
	public function actionRead()
	{
		$data = Yii::$app->request->post();
		if($data){
			$model = new GetProvince();
			$operation = $model->read($data);
			if($operation){
				return ['code'=>200,"msg"=>'成功','data'=>$operation->announcement_id];
			}else{
				return ['code'=>400001,"msg"=>'操作写入失败'];
			}
		}else{
			return ['code'=>400,"msg"=>'未接收到数据'];
		}
		
	}

	//批量已读
	public  function actionReads()
	{
		$data = Yii::$app->request->post();
		if($data){
			$model = new GetProvince();
			$operation = $model->readAll($data['id']);
			if($operation){
				return ['code'=>200,"msg"=>'成功','data'=>$operation->updated_at];
			}else{
				return ['code'=>400001,"msg"=>'操作写入失败'];
			}
			
		}else{
			return ['code'=>400,"msg"=>'未接收到数据'];
		}
	}

	//获取是否有未读公告
	// public  function actionReads()
	// {
	// 	$data = Yii::$app->request->post();
	// 	if($data){
	// 		$model = new GetProvince();
	// 		$data = $model->readAll($data['id']);
	// 		if($data){
	// 			return ['code'=>200,"msg"=>'成功','data'=>$data];
	// 		}else{
	// 			return ['code'=>400,"msg"=>'已读操作失败'];
	// 		}
			
	// 	}else{
	// 		return ['code'=>400,"msg"=>'未接收到数据'];
	// 	}
	// }


	//字典表中的通用配置信息
	//MINI_REVIEW 小程序送审开关
	//MINI_STATEMENT 小程序公告声明
	public function actionConfig()
	{
		$review = Config::getConfigs('MINI_REVIEW');
		$statement = Config::getConfigs('MINI_STATEMENT');
		return ['code'=>200,"msg"=>'成功','review'=>$review,'statement'=>$statement];
	}

}
