<?php
namespace api\models;

use Yii;
use yii\base\Model;
use common\models\Wuser;
use common\models\Cuser;


class Wechat extends Model
{

    //公众号用户处理
    // 1：绑定成功已订阅；5：绑定成功但未订阅；2：已绑定已订阅；6：已绑定但未订阅；3：小程序未订阅。
    public function getUser($openid)
    {
    	$user = Wuser::findByOpenid($openid);

    	$userData =  $user ? $user : $this->createUser($openid);

    	if(is_object($userData)){
    		$miniUser = Cuser::findByUnionid($userData->unionid);
    		if($miniUser){

    			if($miniUser->wopenid){
    				//提示简明，且代码少的情况下全部换成了3
    				//return $miniUser->operation ? 2 : 6;
    				return $miniUser->operation ? 2 : 3;
    			}else{
    				$miniUser->wopenid = $userData->openid;
    				if($miniUser->save()){
    					//提示简明，且代码少的情况下全部换成了3
						//return $miniUser->operation ? 1 : 5;
						return $miniUser->operation ? 1 : 3;
    				}else{
    					return 600;
    				} 				
    			}
    		}else{
    			return 3;
    		}
    	}else{
    		return $userData;
    	}
    }

    //通过openid解析用户信息，写入并返回。
    public function createUser($openid)
    {
    	$data = Yii::$app->wechat->WeChatCustom()->userInfo($openid);

    	if($data['subscribe']==1){
    		$model = new Wuser();
	    	$model->setAttributes($data);
	    	$model->openid = $data['openid'];
	    	$model->unionid = $data['unionid'];
	        $model->nickname = $data['nickname'];
	        $model->sex = $data['sex'];
	        $model->headimgurl = $data['headimgurl'];
	        $model->subscribe = $data['subscribe'];
	        $model->subscribe_time = $data['subscribe_time'];
	        $model->remark = $data['remark'];
	        $model->groupid = $data['groupid'];
	        $model->tagid_list = implode(",",$data['tagid_list']);
	        $model->subscribe_scene = $data['subscribe_scene'];
	        $model->qr_scene = $data['qr_scene'];
	        $model->qr_scene_str = $data['qr_scene_str'];
	       	return $model->save() ? $model : 601;
	       	//return 601;
    	}else{
    		return 602;
    	}
    }

}
