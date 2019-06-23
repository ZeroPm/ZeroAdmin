<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Brand */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="brand-form create_box">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'layui-form']]); ?>

    <!-- <?= $form->field($model, 'id')->textInput()->textInput(['class'=>'layui-input']) ?> -->

    <?= $form->field($model, 'title')->textInput()->textInput(['class'=>'layui-input','maxlength' => 6,'placeholder'=>'展示小程序导航栏标题（最多6个字）']) ?>

    <?= $form->field($model, 'img_src')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input','placeholder'=>'暂时输入图片地址,图片尺寸要求600px*128px']) ?>

    <?= $form->field($model, 'sort')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input','maxlength' => 5,'placeholder'=>'数值越大越前']) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input','placeholder'=>'公众号文章地址']) ?>

    <!-- <?= $form->field($model, 'status')->dropDownList([1=>'已开启','0'=>'已关闭']); ?> -->

    <?= $form->field($model, 'status')->radioList([1=>'已开启','0'=>'已关闭']); ?>

    <!-- <?= $form->field($model, 'status')->textInput(['type'=>'checkbox','lay-skin'=>'switch']) ?> -->

    <!-- <?= $form->field($model, 'status')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'created_at')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'updated_at')->textInput()->textInput(['class'=>'layui-input']) ?> -->

    <div align='right'>
        <?= Html::submitButton($model->isNewRecord ? '添加' : '编辑', ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
    