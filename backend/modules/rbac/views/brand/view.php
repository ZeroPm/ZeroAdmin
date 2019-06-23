<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;

LayuiAsset::register($this);

?>
<div class="brand-view">
    <?= DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
		'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
        'attributes' => [
            'id',
            'title',
            'img_src',
            'sort',
            'link',
            'status',
            [
                'attribute' => 'created_at',
                // 'contentOptions' => ['class'=>'text-center'],
                "format" => ["date", "php:Y-m-d H:i:s"],
                'headerOptions' => [
                    // 'style'=> 'text-align: center;'
                ],
            ],
            [
                'attribute' => 'updated_at',
                // 'contentOptions' => ['class'=>'text-center'],
                "format" => ["date", "php:Y-m-d H:i:s"],
                'headerOptions' => [
                    // 'style'=> 'text-align: center;'
                ],
            ],            
        ],
    ]) ?>

</div>

<!-- <div class="layui-form">
<input type="checkbox" lay-filte="switch" lay-skin="switch" lay-text="ON|OFF">
</div> -->
