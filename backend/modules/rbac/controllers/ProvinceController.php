<?php

namespace rbac\controllers;

use Yii;
use common\models\Province;
use common\models\searchs\Province as ProvinceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\data\Pagination;
use common\models\Announcement;

/**
 * ProvinceController implements the CRUD actions for Province model.
 */
class ProvinceController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
					'delete-all' => ['POST'],
                    'deleteann' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Province models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProvinceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //print_r($dataProvider);exit();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Province model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        //print_r($this->findmodel($id));exit();
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Province model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Province();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            //print_r(Yii::$app->request->post());exit();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Province model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Province model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		if($model->delete()){
			return json_encode(['code'=>200,"msg"=>"删除成功"]);
		}else{
			$errors = $model->firstErrors;
			return json_encode(['code'=>400,"msg"=>reset($errors)]);
		}
    }
    /**
     * Deletes all an existing Province model.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new Province;
            $count = $model->deleteAll(["in","id",$data['keys']]);
            if($count>0){
                return json_encode(['code'=>200,"msg"=>"删除成功"]);
            }else{
				$errors = $model->firstErrors;
                return json_encode(['code'=>400,"msg"=>reset($errors)]);
            }
        }else{
            return json_encode(['code'=>400,"msg"=>"请选择数据"]);
        }
    }
    /**
     * Finds the Province model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Province the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Province::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //编辑链接
    public function actionEditlk()
    {
        $data = Yii::$app->request->post();
        if($data){
            $model = $this->findModel($data['id']);
            //echo $id;exit();
            
            $model->link = $data['link'];
            
            if($model->save()){
                return json_encode(['code'=>200,"msg"=>"编辑成功"]);
            }else{
                $errors = $model->firstErrors;
                return json_encode(['code'=>400,"msg"=>reset($errors)]);
            }
        }else{
            return json_encode(['code'=>400,"msg"=>"数据获取失败"]);
        }       
    }

    //开启操作
    public function actionActive($id)
    {
        $model = $this->findModel($id);
        //echo $id;exit();
        if($model->status== Province::STATUS_ACTIVE){
            return json_encode(['code'=>400,"msg"=>"该省已经是开启状态"]);
        }
        
        $model->status = Province::STATUS_ACTIVE;
        
        if($model->save()){
            return json_encode(['code'=>200,"msg"=>"开启成功"]);
        }else{
            $errors = $model->firstErrors;
            return json_encode(['code'=>400,"msg"=>reset($errors)]);
        }
    }
    
    //关闭操作
    public function actionInactive($id)
    {   
        $model = $this->findModel($id);
        //echo $id;exit();
        if($model->status== Province::STATUS_INACTIVE){
            return json_encode(['code'=>400,"msg"=>"该省已经是关闭状态"]);
        }
        
        $model->status = Province::STATUS_INACTIVE;
        
        if($model->save()){
            return json_encode(['code'=>200,"msg"=>"关闭成功"]);
        }else{
            $errors = $model->firstErrors;
            return json_encode(['code'=>400,"msg"=>reset($errors)]);
        }
    }

    //获取公告
    public function actionGetann($province_id,$limit)
    {

        //有效公告的条件
        $cond = ['province_id' => $province_id, 'status' => [0,1]];

        $count = Announcement::getCount($cond);
        //待处理公告的条件
        $uncond = ['province_id' => $province_id, 'status' => [0]];

        $uncount = Announcement::getCount($uncond);

        $pager = new Pagination(['totalCount' => $count,'pageSize'=>$limit]);//分页

        $items = Announcement::getItems($cond,$limit,$pager);

        $create = Announcement::getCreate($cond);

        $create = $create ? '自'.date('Y-m-d', $create->created_at).'起收录' : '暂未开始收录';

        $data = array('code'=>200, 'msg'=> '加载成功', 'count'=>$count, 'data'=>$items,'uncount'=>$uncount,'create'=>$create);
        
        return Json::encode($data);//自定义返回函数，

    }

    //添加公告
    public function actionCreateann($id)
    {

        
        $model = new Announcement(); 

        if ($model->load(Yii::$app->request->post())) {

            if($model->save()){

                echo '添加成功~';

            }else{

                echo '添加失败~';

            }
        } else {
            return $this->render('createann', [
                'model' => $model,
                'province' => $this->findModel($id),
            ]);
        }
    }

    //删除公告
    public function actionDeleteann()
    {

        $data = Yii::$app->request->post();
        //print_r($data);exit();
        $key = array_column($data['keys'],'id');
        $provinces = array_column($data['keys'],'province_id');
        $condition = ['and',
            ['in', 'province_id', $provinces],
            ['in', 'id', $key],
        ];
        //return json_encode(['code'=>200,"msg"=>"成功",'data'=>$data]);
        if($data){    
            $model = new Announcement();
            $count = $model->updateAll(['status'=>2],$condition);
            //return json_encode(['code'=>200,"msg"=>"成功",'data'=>$key]);exit();
            if($count>0){
                return json_encode(['code'=>200,"msg"=>"删除成功"]);
            }else{
                $errors = $model->firstErrors;
                if($errors){
                    return json_encode(['code'=>400,"msg"=>reset($errors)]);
                }else{
                    return json_encode(['code'=>400,"msg"=>"没有需要删除的数据"]);
                }
            }
        }else{
            return json_encode(['code'=>400,"msg"=>"请选择数据"]);
        }
    }

    //处理公告
    public function actionProcessed()
    {

        $data = Yii::$app->request->post();

        $key = array_column($data['keys'],'id');
        $provinces = array_column($data['keys'],'province_id');
        $condition = ['and',
            ['in', 'province_id', $provinces],
            ['in', 'id', $key],
        ];

        if($data){    
            $model = new Announcement();
            $count = $model->updateAll(['status'=>1],$condition);
            //return json_encode(['code'=>200,"msg"=>"成功",'data'=>$key]);exit();
            if($count>0){
                return json_encode(['code'=>200,"msg"=>"处理成功"]);
            }else{
                $errors = $model->firstErrors;
                if($errors){
                    return json_encode(['code'=>400,"msg"=>reset($errors)]);
                }else{
                    return json_encode(['code'=>400,"msg"=>"没有需要处理的数据"]);
                }
            }
        }else{
            return json_encode(['code'=>400,"msg"=>"请选择数据"]);
        }
    }

}
