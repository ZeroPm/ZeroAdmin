<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model rbac\models\searchs\Province */
/* @var $form yii\widgets\ActiveForm */
AppAsset::register($this);
?>

<div class="province-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
		'fieldConfig' => [
		   'template' => '<div class="layui-inline">{label}：<div class="layui-input-inline">{input}</div></div><span class="help-block" style="display: inline-block;">{hint}</span>',
	   ],
    ]); ?>

<!--     <?= $form->field($model, 'id')->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'province_id')->textInput(['class'=>'layui-input']) ?> -->

    <?= $form->field($model, 'name')->textInput(['class'=>'layui-input']) ?>

<!--     <?= $form->field($model, 'fullname')->textInput(['class'=>'layui-input']) ?> -->

    <?= $form->field($model, 'status')->textInput(['class'=>'layui-input']) ?>

    <?php // echo $form->field($model, 'cidx') ?>

    <?php // echo $form->field($model, 'location') ?>

    <?php // echo $form->field($model, 'link') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

	<?php //$form->field($model, "status")->dropDownList([""=>"全部","0"=>"待审核","1"=>"审核通过"],["class"=>"layui-input"]) ?>

	<div class="form-group">
        <?= Html::submitButton('查找', ['class' => 'layui-btn layui-btn-normal']) ?>
        <?= Html::button('添加', ['class' => 'layui-btn layui-default-add']) ?>
		<?= Html::button('批量删除', ['class' => 'layui-btn layui-btn-danger gridview layui-default-delete-all']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
