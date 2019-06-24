<?php
namespace api\models;

use Yii;
use yii\base\Model;
use common\models\Province;
use common\models\Operation;
use common\models\Cuser;
use common\models\Announcement;
use common\models\Isread;
use common\models\Brand;
use yii\web\NotFoundHttpException;
use yii\db\Command;
use yii\data\Pagination;
/**
 * Login form
 */
class GetProvince extends Model
{
	//省份ID
    public $province_id;
    //用户订阅类型(详情请见content表中的identity的定义)
    public $identity;

    public $openid;    

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
        $model->updated_at = time();
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

    //通过小程序Openid获取用户信息
    public function getUserData($mopenid)
    {
    	return Cuser::findByOpenidm($mopenid);
    }

    //查询省份列表
    public function getItem()
    {
        $model = new Province();
        return $model->find()->select('province_id,name')->all();
        //return 123;
    }

    //获取公告
    public function getAnn($province_id,$openid,$limit)
    {
        $this->openid = $openid;
        $model = new Announcement();
        $count = $model->find()->where(['province_id'=>$province_id,'status'=>1])->count();
        $items['pages'] = new Pagination(['totalCount' => $count,'pageSize'=>$limit]);
        $items['item'] = $model->find()->select('id,province_id,title,link,date,created_at')->where(['province_id'=>$province_id,'status'=>1])->with(
            ['isread'=>function($query){
                $cond = ['openid'=>$this->openid,'isready'=>1];
                $query->andwhere($cond);
                }
            ]
        )->offset($items['pages']->offset)->limit($limit)->orderBy('date DESC')->asArray()->all();

        //$item = $model->find()->select('id,province_id,title,link,status,date')->where(['province_id'=>$province_id])->with(['isread'])->asArray()->all();
        //$items['item'] = $item;

        return $items;

    }

    //记录用户已读
    public function read($data)
    {
        $model = new Isread();
        $model->openid = $data['openid'];
        $model->announcement_id = $data['announcement_id'];
        $model->isready = $data['isread'];
        $model->created_at = time();
        if($model->save()){
            return  $model;
        }else{
            return  false;
        }
    }

    //批量已读使用了Upadated_at时间作为处理标准。这没办法，这种方法最简单。
    public function readAll($id)
    {
        $model = Operation::findById($id);
        $model->updated_at = time();
        if($model->save()){
            return  $model;
        }else{
            return  false;
        }
    }

    public function updateUserSub($user,$province_id,$isub)
    {
        $user->province_id = $province_id;
        $user->isub = $isub;
        if($user->save()){
            return true;
        }else{
            return false;
        }
    }
    // 获取轮播图
    public function getBrand()
    {
        // limit最多8张
        return Brand::find()->select('title,img_src,sort,link,status')->where('status=1')->limit(8)->orderBy('sort DESC')->all();
    }

}
