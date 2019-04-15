<?php
namespace api\models;

use Yii;
use yii\base\Model;
use common\models\Cuser;
use common\models\Operation;

/**
 * Login form
 */
class LoginCuser extends Model
{
    public $openid;
    public $session_key;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // openind  required
            [['openid','session_key'], 'required'],
            
        ];
    }


    //小程序用户处理
    public function getUser($data)
    {
    	if(Cuser::findByOpenidm($this->openid)){
    		$model = Cuser::findByOpenidm($this->openid);
    		$model->updated_at = time();
			$model->session_key = $this->session_key;
            $model->nickname = array_key_exists("nickName",$data) ? $data['nickName'] : '';
            $model->gender = array_key_exists("gender",$data) ? $data['gender'] : '';
            $model->avatarurl = array_key_exists("avatarUrl",$data) ? $data['avatarUrl']:'';
			if(!$model->union_id ){
                if(array_key_exists("unionid",$data)){
                    //小程序登录时的unionid绑定
                    $model->union_id = $data['unionid'];
                }else if(array_key_exists("unionId",$data)){
                    //小程序解密后unionId绑定
                    $model->union_id = $data['unionId'];
                }
			}
			$model->isfollow = array_key_exists("unionid",$data) ? 1 : 0 ;
            $model->save();  
            return  $model;
    	}else{
    		$model = new Cuser();
    		$model->mopenid = $data['openid'];
    		$model->session_key = $this->session_key;
            $model->nickname = array_key_exists("nickName",$data) ? $data['nickName'] : '';
            $model->gender = array_key_exists("gender",$data)?$data['gender'] : '';
            $model->avatarurl = array_key_exists("avatarUrl",$data) ? $data['avatarUrl'] : '';
            if(array_key_exists("unionid",$data)){
                //小程序登录时的unionid绑定
                $model->union_id = $data['unionid'];
            }else if(array_key_exists("unionId",$data)){
                //小程序解密后unionId绑定
                $model->union_id = $data['unionId'];
            }
    		$model->isfollow = array_key_exists("unionid",$data) ? 1 : 0 ;
    		$model->save();
    		return $model;
    	}
    }

    //用户首页，订阅页面数据处理
    public function getUserData($id)
    {	

    	$operation = new Operation();

    	$userdata['first'] = $operation->find()->select(['province_id','type','created_at','isub'])->where(['uuid'=>$id,'type'=>1])->orderBy('created_at DESC')->asArray()->one();

    	$userdata['sub'] = $operation->find()->select(['province_id','type','created_at','isub'])->where(['uuid'=>$id,'type'=>2])->orderBy('created_at DESC')->asArray()->one();
        //静态返回token后续要优化呀，蛋疼
    	$userdata['access_token'] = '2as-xegy1TpHbjqYKVgeWCdGMm6e6Lda_1553947200';
    	
    	return $userdata;
    }

    public function update($openid,$isfollow,$isread)
    {
        $model = Cuser::findByOpenidm($openid);
        //isfollow的更新根据后续运营情况决定是否再次提示
        //$model->isfollow = $isfollow;
        $isread ? $model->isread=$isread : '';
        if($model->save()){
            return $model;
        }else{
            return false;
        }
    }

}
