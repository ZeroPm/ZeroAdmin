<?php

use yii\helpers\Html; 
use yii\widgets\ActiveForm; 

/* @var $this yii\web\View */ 
/* @var $model common\models\Announcement */ 
/* @var $form yii\widgets\ActiveForm */ 
$this->registerJs($this->render('js/create.js'));
?> 

<div class="announcement-form create_box"> 

    <?php $form = ActiveForm::begin(['options' => ['class' => 'layui-form']]); ?>

    <?= $form->field($model, 'province_id')->textInput()->textInput(['class'=>'layui-input layui-disabled','value'=>$province->province_id,'readonly'=>true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'link')->textarea(['rows' => 6])->textInput(['class'=>'layui-input']) ?>

    <?= $form->field($model, 'date')->textInput()->textInput(['class'=>'layui-input', 'id'=>'add-date']) ?>

    <div align='right'> 
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal']) ?> 
    </div> 

    <?php ActiveForm::end(); ?> 

</div> 