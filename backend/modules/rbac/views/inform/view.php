<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="inform-view">
    <?= DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
		'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
        'attributes' => [
            'id',
            'content_id',
            'title',
            'star_time:datetime',
            'inform_doc',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
