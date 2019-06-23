<style type="text/css">
    .layui-form-switch{
        margin-top: 0px;
    }
</style>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\LayuiAsset;
use yii\grid\GridView;
use common\models\Announcement;

LayuiAsset::register($this); 
$this->registerJs($this->render('js/index.js'));
/* @var $this yii\web\View */
/* @var $searchModel rbac\models\searchs\Province */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<blockquote class="layui-elem-quote" style="font-size: 14px;">
		    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
	</blockquote>
<div class="province-index layui-form news_list">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
		'tableOptions'=> ['class'=>'layui-table'],
		'pager' => [
			'options'=>['class'=>'layuipage pull-right'],
				'prevPageLabel' => '上一页',
				//'pageSize' => 10,
				'nextPageLabel' => '下一页',
				'firstPageLabel'=>'首页',
				'lastPageLabel'=>'尾页',
				'maxButtonCount'=>5,
        ],
		'columns' => [
			[
				'class' => 'backend\widgets\CheckboxColumn',
				'checkboxOptions' => ['lay-skin'=>'primary','lay-filter'=>'choose'],
				'headerOptions' => ['width'=>'50','style'=> 'text-align: center;'],
				'contentOptions' => ['style'=> 'text-align: center;']
			],

            //'id',
            // 'province_id',
            // 'name',
            [
            	'attribute' => 'province_id',
				'contentOptions' => ['class'=>'text-center'],
				'headerOptions' => ['style'=> 'text-align: center;'],
            ],
            [
            	'attribute' => 'name',
				'contentOptions' => ['class'=>'text-center'],
				'headerOptions' => ['style'=> 'text-align: center;'],
				'format' => 'raw',
				'value' => function($model){
					return Html::a($model->name,$model->link,['target'=>'view_window']);
				}	
            ],
            [
            	'attribute' => '待处理公告',
				'contentOptions' => ['class'=>'text-center'],
				'headerOptions' => ['style'=> 'text-align: center; color:#337ab7'],
				'format' => 'raw',
				'value' => function($model){
					$unprocessed = Announcement::getCount(['province_id'=>$model->province_id,'status'=>Announcement::STATUS_UNPROCESSED]);
					return $unprocessed > 0 ?'<font style="color:#FFB800;">'.$unprocessed.'</font>': $unprocessed;
				}	
            ],            
            // 'fullname',
            // 'pinyin',
            // 'cidx',
            // 'location',
            // 'link:ntext',
            //'status',

            [
                'attribute' => 'status',
				'contentOptions' => ['class'=>'text-center'],
				'format' => 'raw',
                'value' => function($model){
                    //return $model->status==0?'<font color="red">已关闭</font>':'<font color="green">已开启</font>';
                    //return $model->status==0?'<input type="checkbox" lay-skin="switch" lay-text="ON|OFF"':'<input type="checkbox" lay-skin="switch" lay-text="ON|OFF" checked>';
                    //return '<input type="checkbox" name="xxx" lay-skin="switch">';
                    return $model->status==0?'<font color="red">已关闭</font>':'<font color="green">开启中</font>';
                },
				'headerOptions' => [
					'style'=> 'text-align: center;'
				],
            ],
            
           	[
                'attribute' => 'created_at',
				'contentOptions' => ['class'=>'text-center'],
                "format" => ["date", "php:Y-m-d H:i:s"],
				'headerOptions' => [
					'style'=> 'text-align: center;'
				],
            ],
           	[
                'attribute' => 'updated_at',
				'contentOptions' => ['class'=>'text-center'],
                "format" => ["date", "php:Y-m-d H:i:s"],
				'headerOptions' => [
					'style'=> 'text-align: center;'
				],
            ],            
            

            [
				'header' => '操作',
				'class' => 'yii\grid\ActionColumn',
				'headerOptions' => [
					'width' => '10%'
				],
				'template' =>'{view}',
				'buttons' => [
                    'view' => function ($url, $model, $key){
						return Html::a('查看', Url::to(['view','id'=>$model->id]), ['class' => "layui-btn layui-btn-xs layui-default-view",'name' => $model->name]);
                    },
                    'update' => function ($url, $model, $key) {
						return Html::a('修改', Url::to(['update','id'=>$model->id]), ['class' => "layui-btn layui-btn-normal layui-btn-xs layui-default-update"]);
                    },
					'delete' => function ($url, $model, $key) {
						return Html::a('删除', Url::to(['delete','id'=>$model->id]), ['class' => "layui-btn layui-btn-danger layui-btn-xs layui-default-delete"]);
					}
				]
			],
        ],
    ]); ?>
</div>
