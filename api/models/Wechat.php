<?php
namespace api\models;

use Yii;
use yii\base\Model;
use common\models\Wuser;
use common\models\Cuser;
use common\models\Inform;


class Wechat extends Model
{

    //关注公众号用户处理
    // 1：绑定成功已订阅；5：绑定成功但未订阅；2：已绑定已订阅；6：已绑定但未订阅；3：小程序未订阅。
    public function getUser($openid)
    {
    	$user = Wuser::findByOpenid($openid);

    	$userData =  $user ? $user : $this->createUser($openid);

    	if(is_object($userData)){
    		$userData->subscribe = 1;
    		$userData->save();
    		$miniUser = Cuser::findByUnionid($userData->unionid);
    		if(!empty($miniUser)){

    			if($miniUser->wopenid){
    				//提示简明，且代码少的情况下全部换成了3
    				//return $miniUser->operation ? 2 : 6;
    				$miniUser->isfollow = $userData->subscribe;
    				$miniUser->save();
    				return $miniUser->operation ? 2 : 3;

    			}else{
    				$miniUser->wopenid = $userData->openid;
    				$miniUser->isfollow = $userData->subscribe;
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

    public function unsubScribe($openid)
    {
    	$user = Wuser::findByOpenid($openid);
    	if($user){
	        $user->subscribe = 0;
	        $user->save();
        }
        $miniUser = Cuser::findByUnionid($user->unionid);
        if($miniUser){
            $miniUser->isfollow = 0;
            $miniUser->save();
        }
    }

    //查询出符合条件的通知项。状态为有效且没有发送，发送时间小于当前时间的。
    public function inForm()
    {
        //$inform = new Inform();
        //$cond = ['status'=>1];
        $fristDate = strtotime(date("Y-m-d")." 01:00:00");
        $overDate = strtotime(date("Y-m-d")." 23:59:00");
        $infoData = Inform::find()->select('id,content_id,star_time,inform_doc,status')->where(['<','star_time',time()])->andwhere(['>','star_time',$fristDate])->andwhere(['<','star_time',$overDate])->andwhere(['status'=>1,'processed'=>0])->with(
            [   'content'=>function($query){
                //$cond = ['status'=>1];
                $query->select('id,province_id,status,identity');
                },
                'province'=>function($query){
                //$cond = ['status'=>1];
                $query->select('province_id,status');
                }
            ]
        )->asArray()->all();
        return $infoData;
    }

    //查询出订阅信息相同，已关注，且有公总号openid的用户信息
    public function userData($province_id,$isub)
    {
        //return Cuser::find()->select('mopenid,wopenid,isfollow,province_id,isub,union_id')->where(['province_id'=>$province_id,'isub'=>$isub,'isfollow'=>1])->andwhere('wopenid is NOT NULL')->asArray()->all();
        return Cuser::find()->select('wopenid')->where(['province_id'=>$province_id,'isub'=>$isub])->andwhere('wopenid!=""')->asArray()->all();
    }

    //根据每个通知项组装对应用户信息
    public function inFormData()
    {
        $inFormData = $this->inForm();
        foreach($inFormData as $key=>$value){
            if($value['content']['status']==1&&$value['province']['status']==1){
                $isub = $value['content']['identity']==3?['1','2']:$value['content']['identity'];
                $inFormData[$key]['user'] = $this->userData($value['content']['province_id'],$isub);
            }else{
                $inFormData[$key]['user'] = [];
            }
            $inFormData[$key]['user_count'] = count($inFormData[$key]['user']);
        }
        return $inFormData;
    }

    //通知模板数据组装
    public function templateData($data)
    {
        $expData = explode(";",$data);
        $data = array();
        $data['data'] = array(
            'first'=>array(
                'value'=>$expData[0]
            ),
            'keyword1'=>array(
                'value'=>$expData[1]
            ),
            'keyword2'=>array(
                'value'=>$expData[2]
            ),
            'keyword3'=>array(
                'value'=>$expData[3]
            ),
            'remark'=>array(
                'value'=>$expData[4]
            ),
        );
        $data['template_id'] = "2T2quvQgVeYCUhlTffv6mn0t1gCrdzxRcf0M2c0AUUM";
        $data['miniprogram'] = array(
            'appid' => 'wx8421f195ef6f0716',
            //'pagepath' => 'pages/userCenter/userCenter',
        );
        return $data;
    }

    public function upInformStatus($id,$processed,$processed_total)
    {
        $model = inForm::findOne($id);
        $model->processed = $processed;
        $model->processed_total = $processed_total;
        return $model->save()?true:false;
    }
}
