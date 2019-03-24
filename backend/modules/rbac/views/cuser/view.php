<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;

LayuiAsset::register($this);
?>
<div class="cuser-view">
    <?= DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
		'template' => '<tr><th width="100px">{label}</th><td>{value}</td></tr>', 
        'attributes' => [
            'id',
            'uuid',
            'union_id',
            'nickname',
            'avatarurl',
            'gender',
            'isfollow',
            'created_at',
            'updated_at',
            'wopenid',
            'mopenid',
            'parent_uuid',
        ],
    ]) ?>

</div>
