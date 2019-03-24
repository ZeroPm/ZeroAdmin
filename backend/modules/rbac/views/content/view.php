<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="content-view">
    <?= DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
		'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
        'attributes' => [
            'id',
            'province_id',
            'title',
            'content:ntext',
            'sort',
            'link:ntext',
            'link_type',
            'status',
            'identity',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
