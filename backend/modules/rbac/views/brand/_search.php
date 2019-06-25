<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model common\models\searchs */
/* @var $form yii\widgets\ActiveForm */
AppAsset::register($this);
?>

<div class="brand-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
		'fieldConfig' => [
		   'template' => '<div class="layui-inline">{label}：<div class="layui-input-inline">{input}</div></div><span class="help-block" style="display: inline-block;">{hint}</span>',
	   ],
    ]); ?>

<!--     <?= $form->field($model, 'id')->textInput(['class'=>'layui-input']) ?> -->

    <?= $form->field($model, 'title')->textInput(['class'=>'layui-input']) ?>

<!--     <?= $form->field($model, 'img_src')->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'sort')->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'link')->textInput(['class'=>'layui-input']) ?> -->

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

	<?php //$form->field($model, "status")->dropDownList([""=>"全部","0"=>"待审核","1"=>"审核通过"],["class"=>"layui-input"]) ?>

	<div class="form-group">
        <?= Html::submitButton('查找', ['class' => 'layui-btn layui-btn-normal']) ?>
        <?= Html::button('添加', ['class' => 'layui-btn layui-default-add']) ?>
		<?= Html::button('批量删除', ['class' => 'layui-btn layui-btn-danger gridview layui-default-delete-all']) ?>
        <?= Html::button('长链接转短连接', ['class' => 'layui-btn wechat-shorturl']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- 场链接转短连接 -->
  <div class="layui-form layui-hide shorturl-form" lay-filter="shorturl-form" style="padding:20px;">
      <div class="layui-form-item">
        <label class="layui-form-label">链接地址</label>
        <div class="layui-input-block">
          <input name="shorturl" id="shorturl-edit" lay-verify="required|url" placeholder="请输入链接地址"  class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">转换结果</label>
        <div class="layui-input-block">
          <span class="shorturl-span"></span>
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
            <div align='right'>
            <button class="layui-btn"  lay-submit lay-filter="shorturl-go">编辑</button>
            </div>
        </div>
      </div>
  </div>
