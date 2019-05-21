<?php

namespace rbac\controllers;

use Yii;
use common\models\Inform;
use common\models\searchs\Inform as InformSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Content;
use common\models\Config;

/**
 * InformController implements the CRUD actions for Inform model.
 */
class InformController extends Controller
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
     * Lists all Inform models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InformSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // return $this->render('index', [
        //     'searchModel' => $searchModel,
        //     'dataProvider' => $dataProvider,
        // ]);
    }

    /**
     * Displays a single Inform model.
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
     * Creates a new Inform model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new Inform();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'content' => Content::findModel($id),
            ]);
        }
    }

    //异步添加通知
    public function actionCreateadd()
    {

        $data = Yii::$app->request->post();

        if($data){

            $model = new Inform();

            //return json_encode(['code'=>200,"msg"=>"添加成功",'data'=>$data]);exit();

            $model->content_id = $data['keys']['content_id'];

            $model->title = $data['keys']['title'];

            $model->star_time = strtotime($data['keys']['star_time'].":00:00");

            $model->inform_doc = $data['keys']['first'].";".$data['keys']['keyword1'].";".$data['keys']['keyword2'].";".$data['keys']['keyword3'].";".$data['keys']['remark'];

            if ($model->save()) {

                return json_encode(['code'=>200,"msg"=>"添加成功"]);

            }else{

                return json_encode(['code'=>400,"msg"=>"添加失败"]);

            }
        }else{
            return json_encode(['code'=>400,"msg"=>"无数据需要添加~"]);
        }
    }

    //异步编辑通知
    public function actionUpdates()
    {
        $data = Yii::$app->request->post();
        
        if($data){

            $model = $this->findModel($data['keys']['id']);
            //return json_encode(['code'=>200,"msg"=>"添加成功",'data'=>$data]);exit();

            $model->title = $data['keys']['title'];

            $model->star_time = strtotime($data['keys']['star_time'].":00:00");

            $model->inform_doc = $data['keys']['first'].";".$data['keys']['keyword1'].";".$data['keys']['keyword2'].";".$data['keys']['keyword3'].";".$data['keys']['remark'];
            //return json_encode(['code'=>200,"msg"=>"编辑成功",'data'=>$data]);

            if ($model->save()) {

                return json_encode(['code'=>200,"msg"=>"编辑成功"]);

            }else{

                return json_encode(['code'=>400,"msg"=>"编辑失败"]);

            }
        }else{
            return json_encode(['code'=>400,"msg"=>"无数据需要编辑~"]);
        }
    }

    /**
     * Updates an existing Inform model.
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
                'content' => Content::findModel($model->content_id),
            ]);
        }
    }

    /**
     * Deletes an existing Inform model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
        $model->status = 0;
		if($model->save()){
			return json_encode(['code'=>200,"msg"=>"删除成功"]);
		}else{
			$errors = $model->firstErrors;
			return json_encode(['code'=>400,"msg"=>reset($errors)]);
		}
    }
    /**
     * Deletes all an existing Inform model.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new Inform;
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
     * Finds the Inform model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Inform the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Inform::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //通知测试
    public function actionSend()
    {
        $postData = Yii::$app->request->post();
        if($postData){
            $expData = explode(";",$postData['data']);
            $wechatOpenid = explode(";",Config::getConfig('WECHAT_INFO'));
            $countOpenid = count($wechatOpenid);
            $data = array();

            $data['template_id'] = "2T2quvQgVeYCUhlTffv6mn0t1gCrdzxRcf0M2c0AUUM";
            $data['miniprogram'] = array(
                'appid' => 'wx8421f195ef6f0716',
                //'pagepath' => 'pages/userCenter/userCenter',
            );
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
            if($countOpenid>1){
                foreach ($wechatOpenid as $value) {
                    $data['touser'] = $value;
                    Yii::$app->wechat->WeChatTemplate()->send($data);
                }
            }else{
                $data['touser'] = $wechatOpenid[0];
                Yii::$app->wechat->WeChatTemplate()->send($data);
            }
            return json_encode(['code'=>200,"msg"=>'发送成功','data'=>$data]);
            //$data['data'] = 
        }else{
            return json_encode(['code'=>400,"msg"=>'未接受到数据']);
        }
        
    }
}
