<?php

namespace rbac\controllers;

use Yii;
use common\models\Cuser;
use common\models\searchs\Cuser as CuserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Operation;

/**
 * CuserController implements the CRUD actions for Cuser model.
 */
class CuserController extends Controller
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
     * Lists all Cuser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CuserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cuser model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $model = Cuser::find()->where(['id'=>$id])->with('wuser')->one();
        // print_r($model->wuser->openid);exit();
        return $this->render('view', [
            // 'model' => $this->findModel($id),
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Cuser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    // public function actionCreate()
    // {
    //     $model = new Cuser();

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     } else {
    //         return $this->render('create', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    //获取用户操作信息
    public function actionOperation($id)
    {

        $model = new operation();

        $cond = ['uuid' => $id];

        $onetype = array();

        $twotype = array();
        //$items = $model->find()->where($cond)->with('operation')->orderBy(['created_at'=>SORT_DESC])->asArray()->all();

        $items = $model->find()->where($cond)->with(['province'=>function($query){$query->select('name,province_id');}])->orderBy(['created_at'=>SORT_DESC])->asArray()->all();

        foreach ($items as $key => $value) {

            if($items[$key]['type']==1){

                $onetype[] = $items[$key];

            }else{

                $twotype[] = $items[$key];

            }
            //print_r($onetype);
        }
        //print_r($onetype);exit();
        return json_encode(['code'=>200,"msg"=>"成功",'onedata'=>$onetype,'twodata'=>$twotype,'item'=>$items]);
    }

    /**
     * Updates an existing Cuser model.
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
     * Deletes an existing Cuser model.
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
     * Deletes all an existing Cuser model.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteAll(){
        $data = Yii::$app->request->post();
        if($data){
            $model = new Cuser;
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
     * Finds the Cuser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cuser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cuser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
