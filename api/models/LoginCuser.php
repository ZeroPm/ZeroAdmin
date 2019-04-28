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
    public function getUser($data,$parent_uuid)
    {
        $model = Cuser::findByOpenidm($this->openid);
    	if($model){
    		$model->updated_at = time();
			$model->session_key = $this->session_key;
			if(!$model->union_id ){
                $model->union_id = array_key_exists("unionid",$data) ? $data['unionid']: '';
			}
			$model->isfollow = array_key_exists("unionid",$data) ? 1 : 0 ;
            $model->save();  
            return  $model;
    	}else{
    		$model = new Cuser();
    		$model->mopenid = $this->openid;
    		$model->session_key = $this->session_key;
            //随缘，能成则成，不能成则算了
            $model->parent_uuid = $parent_uuid ? $parent_uuid : '';
            
            //小程序登录时的unionid绑定
            $model->union_id = array_key_exists("unionid",$data) ? $data['unionid']: '';
            
    		$model->isfollow = array_key_exists("unionid",$data) ? 1 : 0 ;
    		$model->save();
    		return $model;
    	}
    }

    //用户首页，订阅页面数据处理
    public function getUserData($id)
    {	

    	$operation = new Operation();

    	$userdata['first'] = $operation->find()->select(['id','province_id','type','created_at','isub','updated_at'])->where(['uuid'=>$id,'type'=>1])->orderBy('created_at DESC')->asArray()->one();

    	$userdata['sub'] = $operation->find()->select(['id','province_id','type','created_at','isub','updated_at'])->where(['uuid'=>$id,'type'=>2])->orderBy('created_at DESC')->asArray()->one();
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

    //小程序用户处理
    public function getUsers($data)
    {
        $model = Cuser::findByOpenidm($this->openid);
        if($model){
            $model->updated_at = time();
            $model->session_key = $this->session_key;
            $model->nickname = array_key_exists("nickName",$data) ? $data['nickName'] : '';
            $model->gender = array_key_exists("gender",$data) ? $data['gender'] : '';
            $model->avatarurl = array_key_exists("avatarUrl",$data) ? $data['avatarUrl']:'';
            if(!$model->union_id ){
                $model->union_id = array_key_exists("unionId",$data) ? $data['unionId'] : '';
            }
            $model->save();  
            return  $model;
        }else{
            $model = new Cuser();
            $model->mopenid = $this->openid;
            $model->session_key = $this->session_key;
            $model->nickname = array_key_exists("nickName",$data) ? $data['nickName'] : '';
            $model->gender = array_key_exists("gender",$data)?$data['gender'] : '';
            $model->avatarurl = array_key_exists("avatarUrl",$data) ? $data['avatarUrl'] : '';
            $model->union_id = array_key_exists("unionId",$data) ? $data['unionId'] : '';
            $model->save();
            return $model;
        }
    }

}
