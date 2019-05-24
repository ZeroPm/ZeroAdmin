<?php

namespace api\modules\v1\controllers;

use yii;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;
use common\models\Announcement;

class ArticleController extends ActiveController
{	
    public $modelClass = 'common\models\UserRank';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
	
    public function behaviors() {
        return ArrayHelper::merge (parent::behaviors(), [ 
                'authenticator' => [ 
                    'class' => QueryParamAuth::className()
                ] 
        ] );
    }

    public function actionAddan()
    {
        $data = Yii::$app->request->post();
        $model = new Announcement;
        if($data){
            $announcement = $model->find()->where(['province_id'=>$data['province_id']])->orderBy('date DESC')->one();
            $allData = $this->add($data,$announcement ? strtotime($announcement->date." 00:00:00"): '');
            return ['code'=>200,"msg"=>$allData['msg']];
            // $date = strtotime("2009-5-9 00:00:00");
            // $dates = date("Y-m-d",$date);
        }else{
            return ['code'=>400,"msg"=>'未接收数据'];
        }
        
    }

    //初始化写入或增量更新
    public function add($data,$date)
    {
        $num = 0;
        $cond_date = $date ? $date : $data['init_time'];
        foreach ($data["data"] as $key => $value) {
            $newDate = strtotime($value['date']." 00:00:00");
            if($newDate>$cond_date){
                //防止抓取的数据格式不规则，在date上面再由时间戳转换成日期
                $this->create($data['province_id'],$value['title'],$value['href'],date("Y-m-d",$newDate));
                $num = ++$num;
            }    
        }
        return ['code'=>200,'msg'=>$date?'增量公告'.$num.'条':'初始化数据'.$num.'条'];
    }

    public function create($province_id,$title,$link,$date)
    {
        $model = new Announcement;
        $model->province_id = $province_id;
        $model->title = $title;
        $model->link = $link;
        $model->date = $date;
        $model->save();
    }
}
