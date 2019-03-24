<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\assets\LayuiAsset;
/* @var $this yii\web\View */
/* @var $model common\models\Content */
/* @var $form yii\widgets\ActiveForm */
LayuiAsset::register($this); 
$this->registerJs($this->render('js/index.js'));
?>
<style type="text/css">
    .layui-form-label{
        width: 100px;
    }
</style>
<div style="padding-top: 20px;">
    <form class="layui-form" lay-filter="content-form">
      <div class="layui-form-item">
        <label class="layui-form-label">省份ID</label>
        <div class="layui-input-block">
          <input type="text" name="province_id" readonly=true  class="layui-input layui-disabled" value="<?= $province->province_id; ?>">
        </div>
        <input type="text" name="id" readonly=true  class="layui-input layui-disabled layui-hide" value="<?= $model->id; ?>">
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>标题</label>
        <div class="layui-input-block">
          <input type="text" name="title" required  lay-verify="required" placeholder="请输入标题" lay-verType="tips" autocomplete="off" class="layui-input" value="<?= $model->title; ?>">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>排序</label>
        <div class="layui-input-block">
          <input type="text" name="sort" required lay-verify="required|number" placeholder="请输入排序（越大越前）" lay-verType="tips" autocomplete="off" class="layui-input" value="<?= $model->sort; ?>">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">用户类型</label>
        <div class="layui-input-block">
          <input type="checkbox" name="unregister" value="1" title="未报名" <?= ($model->identity==1||$model->identity==3) ? 'checked' : '' ?> >
          <input type="checkbox" name="registered" value="2" title="已报名" <?= ($model->identity==2||$model->identity==3) ? 'checked' : '' ?>>
        </div>
      </div>   
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>链接类型</label>
        <div class="layui-input-block">
            <select name="link-type" lay-verify="required" lay-verType="tips">
                <option value="">选择链接类型</option>
                <option value="1" <?= $model->link_type==1 ? 'selected' : ''  ?>>自考文章</option>
                <option value="2" <?= $model->link_type==2 ? 'selected' : ''  ?>>公众号文章</option>
            </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>链接地址</label>
        <div class="layui-input-block">
          <input type="text" name="link" required  lay-verify="required|url" lay-verType="tips" placeholder="请链接地址" autocomplete="off" class="layui-input" value="<?= $model->link; ?>">
        </div>
      </div>
      <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">文本域</label>
        <div class="layui-input-block">
          <textarea id="content-text" name="content" style="display: none;"><?= $model->content; ?></textarea>
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
            <div align='right'>
            <!-- <?= Html::submitButton($model->isNewRecord ? '添加' : '编辑', ['class' => $model->isNewRecord ? 'layui-btn' : 'layui-btn layui-btn-normal','lay-filter'=>'go']) ?> -->
            <button class="layui-btn" lay-submit lay-filter="go"><?= $model->isNewRecord ? '添加' : '编辑'?></button>
            </div>
        </div>
      </div>
    </form>
</div>
