<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\LayuiAsset;
use yii\grid\GridView;
use common\models\Operation;
LayuiAsset::register($this); 
$this->registerJs($this->render('js/index.js'));
/* @var $this yii\web\View */
/* @var $searchModel common\models\searchs\Cuser */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<blockquote class="layui-elem-quote" style="font-size: 14px;">
		    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
	</blockquote>
<div class="cuser-index layui-form news_list">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
		'tableOptions'=> ['class'=>'layui-table'],
		'pager' => [
			'options'=>['class'=>'layuipage pull-right'],
				'prevPageLabel' => '上一页',
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

            'mopenid',
            'union_id',
            [
				'attribute' => 'avatarurl',
				'contentOptions' => ['style'=> 'text-align: center;'],
				'headerOptions' => ['width'=>'110','style'=> 'text-align: center;'],
                "format"=>[
                    "image",
                    [
                        "width"=>"30px",
                        "height"=>"30px",
                    ],
                ],
			],
            //'nickname',
            [
            	'attribute' => 'nickname',
				'contentOptions' => ['class'=>'text-center'],
				'headerOptions' => ['style'=> 'text-align: center;'],
            ],
            [
            	'attribute' => 'gender',
				'contentOptions' => ['class'=>'text-center'],
				'headerOptions' => ['style'=> 'text-align: center;'],
				'format' => 'raw',
				'value' => function($model){
					if($model->gender==1){
						return '<font color="blue">男</font>';
					}else if($model->gender==2){
						return '<font color="red">女</font>';
					}else{
						return '未知';
					}
				}
            ],
            //'gender',
            [
            	'attribute' => 'isfollow',
				'contentOptions' => ['class'=>'text-center'],
				'headerOptions' => ['style'=> 'text-align: center;'],
				'format' => 'raw',
				'value' => function($model){
					return $model->isfollow==1 ? '<font color="green">已关注</font>' : '未关注';
				}
            ],
            //'isfollow',
            [
            	'attribute' => '业务概况',
				'contentOptions' => ['class'=>'text-center'],
				'headerOptions' => ['style'=> 'text-align: center; color:#337ab7'],
				'format' => 'raw',
				'value' => function($model){
					
					return Operation::isSub($model->id) ? '<font color="green">已订阅</font>':'未订阅';
				}	
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
            // 'wopenid',
            // 'mopenid',
            // 'parent_uuid',

            [
				'header' => '操作',
				'class' => 'yii\grid\ActionColumn',
				'headerOptions' => [
					'width' => '5%'
				],
				'template' =>'{view}',
				'buttons' => [
                    'view' => function ($url, $model, $key){
						return Html::a('查看', Url::to(['view','id'=>$model->id]), ['class' => "layui-btn layui-btn-xs layui-default-view"]);
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
