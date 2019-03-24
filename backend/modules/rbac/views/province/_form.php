<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model rbac\models\Province */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="province-form create_box">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'layui-form']]); ?>

    <?= $form->field($model, 'province_id')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'pinyin')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'cidx')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'link')->textarea(['rows' => 6])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'status')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'created_at')->textInput()->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'updated_at')->textInput()->textInput(['class'=>'layui-input']) ?>

    <div align='right'>
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
