<?php
namespace api\models;

use Yii;
use yii\base\Model;
use common\models\Province;
use common\models\Operation;
use common\models\Cuser;
use yii\web\NotFoundHttpException;
/**
 * Login form
 */
class GetProvince extends Model
{
	//省份ID
    public $province_id;
    //用户订阅类型(详情请见content表中的identity的定义)
    public $identity;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // openind  required
            [['province_id','identity'], 'required'],
            
        ];
    }


    //用户可见内容处理
    public function getContent()
    {
    	$model = new Province();
    	$item = $model->find()->select('province_id,name,status')->where(['province_id'=>$this->province_id])->with(
    		['content'=>function($query){
    			$cond = ['status'=>1,'identity'=>$this->identity];
    			$query->select('title,province_id,content,sort,link,link_type,status,identity')->andwhere($cond)->orderBy('sort DESC');
    			}
    		]
    	)->asArray()->one();
    	return $item;
    }

    //记录用户操作
    public function addOperation($uuid,$type,$province_id,$isub)
    {
    	$model = new Operation;
    	$model->uuid = $uuid;
    	$model->type = $type;
    	$model->province_id = $province_id;
    	$model->isub = $isub;
    	$model->created_at = time();
    	if($model->save()){
    		return  $model;
    	}else{
    		return  false;
    	}
    }

    //地图API得到省名匹配省份信息
    public function getProvince($name)
    {
    	//return $name;
    	return Province::findName($name);
    }

    
    public function getUserData($mopenid)
    {
    	return Cuser::findByOpenidm($mopenid);
    }

}
