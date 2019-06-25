<?php

namespace rbac\controllers;

use Yii;
use common\models\Content;
use common\models\searchs\Content as ContentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Province;
use yii\helpers\Json;
use yii\db\Expression;
use yii\data\Sort;

/**
 * ContentController implements the CRUD actions for Content model.
 */
class ContentController extends Controller
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
                ],
            ],
        ];
    }

    /**
     * Lists all Content models.
     * @return mixed
     */
    // public function actionIndex()
    // {
    //     $searchModel = new ContentSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //     ]);
    // }

    /**
     * Displays a single Content model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Content model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Content();

        if ($model->load(Yii::$app->request->post())) {
            //&& $model->save()
            return json_encode(['code'=>200,"msg"=>"添加成功",'data'=>Yii::$app->request->post()]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'province' => Province::findModel($id),
            ]);
        }
    }

    //异步添加内容
    public function actionCreateadd()
    {

        $data = Yii::$app->request->post();

        
        if($data){

            $model = new Content();
            //return json_encode(['code'=>200,"msg"=>"添加成功",'data'=>$data]);exit();
            $model->province_id = $data['keys']['province_id'];

            $model->title = $data['keys']['title'];

            $model->content = $data['keys']['content'];

            $model->sort = $data['keys']['sort'];

            $model->link_type = $data['keys']['link-type'];

            $model->link = $data['keys']['link'];

            $model->identity = $data['keys']['identity'];

            if(empty($data['keys']['content'])&&empty($data['keys']['link'])){
              $model->status = 0;
            }

            //return json_encode(['code'=>200,"msg"=>"添加成功",'data'=>$data]);

            if ($model->save()) {

                return json_encode(['code'=>200,"msg"=>"添加成功",'data'=>'link:'.$data['keys']['link'].'content:'.$data['keys']['content']]);

            }else{

                return json_encode(['code'=>400,"msg"=>"添加失败"]);

            }
        }else{
            return json_encode(['code'=>400,"msg"=>"无数据需要添加~"]);
        }
    }

    /**
     * Updates an existing Content model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            $data = Yii::$app->request->post();
            //return $this->redirect(['view', 'id' => $model->id]);&& $model->save()
            return json_encode(['code'=>200,"msg"=>"编辑成功",'data'=>$data]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'province' => $model,
            ]);
        }
    }

    //异步编辑内容
    public function actionUpdates()
    {
        $data = Yii::$app->request->post();
        
        if($data){

            $model = $this->findModel($data['keys']['id']);
            //return json_encode(['code'=>200,"msg"=>"添加成功",'data'=>$data]);exit();

            $model->title = $data['keys']['title'];

            $model->content = $data['keys']['content'];

            $model->sort = $data['keys']['sort'];

            $model->link_type = $data['keys']['link-type'];

            $model->link = $data['keys']['link'];

            $model->identity = $data['keys']['identity'];

            if(empty($data['keys']['content'])&&empty($data['keys']['link'])){
              $model->status = 0;
            }

            //return json_encode(['code'=>200,"msg"=>"编辑成功",'data'=>$data['keys']['content']]);

            if ($model->save()) {

                return json_encode(['code'=>200,"msg"=>"编辑成功",'data'=>'link:'.$data['keys']['link'].'content:'.$data['keys']['content']]);

            }else{

                return json_encode(['code'=>400,"msg"=>"编辑失败"]);

            }
        }else{
            return json_encode(['code'=>400,"msg"=>"无数据需要编辑~"]);
        }
    }
    /**
     * Deletes an existing Content model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
  //   public function actionDelete($id)
  //   {
		// $model = $this->findModel($id);
		// if($model->delete()){
		// 	return json_encode(['code'=>200,"msg"=>"删除成功"]);
		// }else{
		// 	$errors = $model->firstErrors;
		// 	return json_encode(['code'=>400,"msg"=>reset($errors)]);
		// }
  //   }
  //   /**
  //    * Deletes all an existing Content model.
  //    * @param integer $id
  //    * @return mixed
  //    */
  //   public function actionDeleteAll(){
  //       $data = Yii::$app->request->post();
  //       if($data){
  //           $model = new Content;
  //           $count = $model->deleteAll(["in","id",$data['keys']]);
  //           if($count>0){
  //               return json_encode(['code'=>200,"msg"=>"删除成功"]);
  //           }else{
		// 		$errors = $model->firstErrors;
  //               return json_encode(['code'=>400,"msg"=>reset($errors)]);
  //           }
  //       }else{
  //           return json_encode(['code'=>400,"msg"=>"请选择数据"]);
  //       }
  //   }
    /**
     * Finds the Content model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Content the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Content::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //异步省份内容及通知
    public function actionItem($province_id)
    {
        $model = new Content();

        $cond = ['province_id' => $province_id];

        $items = $model->find()->where($cond)->with(['inform'=>function($query){$query->andWhere('status = 1');}])->orderBy(['status'=>SORT_DESC,'sort'=>SORT_DESC,'created_at'=>SORT_DESC])->asArray()->all();

        //print_r($items);exit();
        // foreach ($items as $value) {
        //   $informs = $value->inform;
        // }

        $data = array('code'=>200, 'msg'=> '加载成功','data'=>$items);
        
        return Json::encode($data);
    }

    //开启操作
    public function actionActive($id)
    {
        $model = $this->findModel($id);
        //echo $id;exit();
        if($model->status== Content::STATUS_ACTIVE){
            return json_encode(['code'=>400,"msg"=>"该内容已经是开启状态"]);
        }

        if(empty($model->content)&&empty($model->link)){
            return json_encode(['code'=>403,"msg"=>"只有标题不可开启，请完善内容信息"]);
        }
        
        $model->status = Content::STATUS_ACTIVE;
        
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
            return json_encode(['code'=>400,"msg"=>"该内容已经是关闭状态"]);
        }
        
        $model->status = Province::STATUS_INACTIVE;
        
        if($model->save()){
            return json_encode(['code'=>200,"msg"=>"关闭成功"]);
        }else{
            $errors = $model->firstErrors;
            return json_encode(['code'=>400,"msg"=>reset($errors)]);
        }
    }
}
