<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\LayuiAsset;
/* @var $this yii\web\View */
/* @var $model common\models\Inform */
/* @var $form yii\widgets\ActiveForm */
LayuiAsset::register($this); 
$this->registerJs($this->render('js/index.js'));

$keywords = explode(';',$model->inform_doc);
//echo $keywords[0];
?>

<style type="text/css">
    .layui-form-label{
        width: 110px;
    }
</style>
<div style="padding-top: 20px;">
    <form class="layui-form" lay-filter="content-form">
      <div class="layui-form-item">
        <label class="layui-form-label">内容ID</label>
        <div class="layui-input-block">
          <input type="text" name="content_id" readonly=true  class="layui-input layui-disabled" value="<?= $content->id; ?>">
        </div>
        <input type="text" name="id" readonly=true  class="layui-input layui-disabled layui-hide" value="<?= $model->id; ?>">
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>标题</label>
        <div class="layui-input-block">
          <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题(仅供后台使用)" lay-verType="tips" autocomplete="off" class="layui-input" value="<?= $model->title; ?>">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>通知时间</label>
        <div class="layui-input-block">
          <input type="text" name="star_time" required  lay-verify="required" placeholder="选择通知时间" lay-verType="tips" autocomplete="off" class="layui-input" id="star-date" value="<?= $model->star_time ? date('Y-m-d H', $model->star_time):''; ?>">
        </div>
      </div>
      
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>first</label>
        <div class="layui-input-block">
          <input type="text" name="first" required  lay-verify="required" lay-verType="tips" placeholder="请输入首句" autocomplete="off" class="layui-input" value="<?= $model->inform_doc ? $keywords[0] : '' ; ?>">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>keyword1</label>
        <div class="layui-input-block" >
          <input type="text" name="keyword1" required  lay-verify="required" lay-verType="tips" placeholder="请输入内容" autocomplete="off" class="layui-input" value="<?= $model->inform_doc ? $keywords[1] : '' ; ?>">
        </div>
        <!-- <div><a class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe640;</i></a></div> -->
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>keyword2</label>
        <div class="layui-input-block" >
          <input type="text" name="keyword2" required  lay-verify="required" lay-verType="tips" placeholder="请输入内容" autocomplete="off" class="layui-input" value="<?= $model->inform_doc ? $keywords[2] : '' ; ?>">
        </div>
        <!-- <div><a class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe640;</i></a></div> -->
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>keyword3</label>
        <div class="layui-input-block" >
          <input type="text" name="keyword3" required  lay-verify="required" lay-verType="tips" placeholder="请输入内容" autocomplete="off" class="layui-input" value="<?= $model->inform_doc ? $keywords[3] : '' ; ?>">
        </div>
        <!-- <div><a class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe640;</i></a></div> -->
      </div>
<!--       <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
          <a class="layui-btn add-keyword">+添加内容项</a>
        </div>
      </div> -->
      <div class="layui-form-item">
        <label class="layui-form-label">remark</label>
        <div class="layui-input-block">
          <input type="text" name="remark"   lay-verType="tips" placeholder="请输入备注" autocomplete="off" class="layui-input" value="<?= $model->inform_doc ? $keywords[4] : '' ; ?>">
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
            <div align='right'>
            <button class="layui-btn" lay-submit lay-filter="go"><?= $model->isNewRecord ? '添加' : '编辑'?></button>
            </div>
        </div>
      </div>
    </form>
</div>