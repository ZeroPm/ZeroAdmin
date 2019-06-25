<?php

use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\LayuiAsset;
use yii\grid\GridView;
LayuiAsset::register($this); 
$this->registerJs($this->render('js/index.js'));
/* @var $this yii\web\View */
/* @var $searchModel common\models\searchs */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<blockquote class="layui-elem-quote" style="font-size: 14px;">
		    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
	</blockquote>
<div class="brand-index layui-form news_list">
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

            // 'id',
            'title',
            // 'img_src',
            [
				'attribute' => 'img_src',
				'contentOptions' => ['style'=> 'text-align: center;'],
				'headerOptions' => ['width'=>'340','style'=> 'text-align: center;'],
                "format"=>[
                    "image",
                    [
                        // "width"=>"320",
                        "height"=>"128",
                        "style"=>"max-width:320px;"
                    ],
                ],
			],
            'sort',
            // 'link',
    //         [
    //         	'attribute' => 'link',
				// 'contentOptions' => ['class'=>'text-center'],
				// 'headerOptions' => ['style'=> 'text-align: center;'],
				// 'format' => 'raw',
				// 'value' => function($model){
				// 	return Html::a($model->link,$model->link,['target'=>'view_window']);
				// }	
    //         ],
            [
            	'attribute' => 'shorturl',
				'contentOptions' => ['class'=>'text-center'],
				'headerOptions' => ['style'=> 'text-align: center;'],
				'format' => 'raw',
				'value' => function($model){
					return Html::a($model->shorturl,$model->shorturl,['target'=>'view_window'])."</br>".Html::a($model->link,$model->link,['target'=>'view_window']);
				}	
            ],
            [
                'attribute' => 'status',
				'contentOptions' => ['class'=>'text-center'],
				'format' => 'raw',
                'value' => function($model){
                    return $model->status==0?'<font color="red">已关闭</font>':'<font color="green">已开启</font>';
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
				'template' =>'{view} {update} {delete}',
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
<!-- <div class="layui-form">
<input type="checkbox" lay-filte="filter" lay-skin="switch" lay-text="ON|OFF">
</div> -->