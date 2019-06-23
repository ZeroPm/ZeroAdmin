<style type="text/css">
    .layui-form-switch{
      margin-top: 0px;
    }
    .content-text{
      line-height:38px;
    }
    .layui-form-label{
      width: 100px;
    }
</style>
<script type="text/javascript">
  function timestampToTime(timestamp) {
      var date = new Date(timestamp*1000);//如果date为13位不需要乘1000
      var Y = date.getFullYear() + '-';
      var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
      var D = (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + ' ';
      var h = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':';
      var m = (date.getMinutes() <10 ? '0' + date.getMinutes() : date.getMinutes()) + ':';
      var s = (date.getSeconds() <10 ? '0' + date.getSeconds() : date.getSeconds());
      return Y+M+D+h+m+s;
  }

  function linkType(num){
    //console.log(num);
    switch(num){
      case '1':
      return '自考公告';
      break;
      case '2':
      return '公众号文章';
      break;
    }
  }

  // 获取当前时间戳(以s为单位)
  var timestamp = Date.parse(new Date());
  timestamp = timestamp / 1000;
  //当前时间戳为：1403149534
  console.log("当前时间戳为：" + timestamp);
  console.log("当前时间：" + timestampToTime(timestamp));
  var stringTimeNow = timestampToTime(timestamp);

  //日期转换成时间戳
  var stringTimeEnd = stringTimeNow.substring(0,11)+"22:30:00";
  var timestampEnd = Date.parse(new Date(stringTimeEnd));
  timestampEnd = timestampEnd / 1000;

  var stringTimeStart = stringTimeNow.substring(0,11)+"00:00:00";
  var timestampStart = Date.parse(new Date(stringTimeStart));
  timestampStart = timestampStart / 1000;

  console.log(stringTimeStart + "当天开始的时间戳为：" + timestampStart);

  console.log(stringTimeEnd + "当天定时任务终止的时间戳为：" + timestampEnd);
  //通知信息边框处理
  function informStatus(processed,star_time){
    if(processed==2){
      return "content-success";
    }else if(processed==0&&timestamp-star_time>7200&&timestamp>star_time){
      return "content-fail";
    }else if(processed==1){
      return "content-warning";
    }else if(timestamp<star_time){
      return "content";
    }else if(processed==0&&timestamp-star_time<7200&&timestamp>star_time){
      return "content";
    }
  }

  function informTime(star_time){
    if(timestampStart < star_time && star_time < timestampEnd){

      return "layui-bg-orange";
    }else{

      return "";
    }
  }
</script>
<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;
use yii\helpers\Html;
//print_r($isubData['total']);
//print_r(Yii::$app->memcache->get('isubData'.$model->province_id));
LayuiAsset::register($this);
$this->registerJs($this->render('js/view.js'));
$this->registerCss($this->render('css/view.css'));

?>
<!-- 隐藏域放至对应省份ID -->
<input class="layui-hide" value=<?= $model->province_id ?> id="province_id">
<!-- 公告table工具栏渲染 -->
<script type="text/html" id="toolbar">
  <div class="layui-btn-container">
    <button class="layui-btn layui-btn-sm" lay-event="add-ann">+添加公告</button>
    <button class="layui-btn layui-btn-sm" lay-event="processed">批量处理</button>
    <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="delete-ann">删除公告</button>
</div>
</script>
<!-- 公告table title渲染链接模板设置 -->
<script type="text/html" id="titleTpl">
  {{#  if(d.status === 0){ }}
  <a href={{d.link}} style="color:#FFB800;" target="_blank" title="{{ d.title }}">{{ d.title }}</a>
  {{#  } else { }}
  <a href={{d.link}} style="color:#666;" target="_blank" title="{{ d.title }}">{{ d.title }}</a>
  {{#  } }}
</script>
<!-- 公告待处理数量及收录时间 -->
<script type="text/html" id="uncoutTpl">
    <span style="color: #FFB800">待处理{{d.uncount}}条</span>&nbsp&nbsp&nbsp&nbsp{{d.create}}
</script>

<div class="layui-form ">

    <blockquote class="layui-elem-quote layui-quote-nm">
    省份基础资料
    <!-- 基础资料tip提示 -->
    <span class="icon-about icon-about-title"><i class="layui-icon layui-icon-tips"></i></span>
    </blockquote>
    <!-- 基础资料 -->
    <div class="layui-row layui-fluid" style="margin-bottom: 10px; ">
        <div class="layui-col-md3">
            <div class="grid-basis "><span style="font-size: 24px;"><?= $model->name ?></span></div>
        </div>
        <div class="layui-col-md1">
            <div class="grid-basis "><span><?= $model->province_id ?></span></div>
        </div>
        <div class="layui-col-md3">
            <div class="grid-basis">
                <div class="layui-elip layui-col-md9"><a target="view_window" href="<?= $model->link ?>" title="<?= $model->link ?>"><?= $model->link ?></a></div>
                <div class="layui-col-md3 "><i class="layui-icon layui-icon-edit updata-link" title="编辑链接" style="font-size: 25px;"></i></div>
            </div>
        </div>
        <div class="layui-col-md2">
            <div class="grid-basis">
                <?= 
                    Html::input('checkbox',$model->id,$model->status,['lay-skin'=>'switch','lay-text'=>'ON|OFF',$model->status==0?'':'checked'=>'checked',"lay-filter"=>'province-switch']);
                ?>
            </div>
        </div>
        <div class="layui-col-md3">
          <div style="line-height:25px;">
            <li>订阅总人数：<?= $isubData['total']; ?></li>
            <li>未报名：<?= $isubData['un_sign']; ?></li>
            <li>已报名：<?= $isubData['is_sign']; ?></li>
          </div>
        </div>
    </div>
    <div class="layui-row layui-fluid">
      <div class="layui-col-md12">
          <fieldset>
            <legend>
              <span style="font-size: 16px;">省份资料笔记</span>
              <i class="layui-icon layui-icon-edit updata-remark" title="编辑笔记" style="font-size: 25px;"></i>
            </legend>
          </fieldset>
      </div>
    </div>
    <div  class="province-remark"><?= $model->remark ?></div>
<!--     <div class="layui-row layui-fluid" style=" ">
      <div class="layui-col-md12">
          
      </div>
    </div> -->
    <blockquote class="layui-elem-quote layui-quote-nm">
    公告
    <!-- 待处理及收录时间的容器 -->
    <span class="an-total" id="uncount">
    </span>
      <!-- 公告tip提示 -->
      <span class="manual"><a href="#">公告整理手册</a></span>
      <span class="icon-about icon-about-ann"><i class="layui-icon layui-icon-tips"></i></span>
    </blockquote>
    <!-- 公告 -->
    <table id="ancontent" lay-filter="ancontent"></table>
    
    <blockquote class="layui-elem-quote layui-quote-nm">
    内容及通知
      <!-- 内容tip提示 -->
      <span class="icon-about icon-about-con"><i class="layui-icon layui-icon-tips"></i></span>

    </blockquote>
    <!-- 内容及通知 -->
    <div style="text-align:center;" class="layui-hide content-loading"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i>加载中...</div>
    <div id="content-table">
    <script type="text/html" id='contentTpl'>
      {{# if(d.length!=0){ }}
      {{# Object.keys(d).forEach(function(key){ }}
        <div class="layui-col-md12 content" >
                <div class=" layui-row layui-col-space10 grid-content">
                  <div class=" layui-col-md4">
                    <div class="content-text">
                      <label class="layui-form-label">标题：</label><div class="layui-input-block layui-bg-gray">{{ d[key]['title'] }}</div>
                    </div>
                  </div>
                  <div class=" layui-col-md2">
                    <div class="content-text">
                      <label class="layui-form-label">排序：</label>{{ d[key]['sort'] }}
                    </div>
                  </div>
                  <div class=" layui-col-md3">
                    <div class="content-text">

                      {{# if(d[key]['identity']==3){ }}
                      <input type="checkbox"  title="未报名"  checked>
                      <input type="checkbox"  title="已报名"  checked>
                      {{# }if(d[key]['identity']==1){ }}
                      <input type="checkbox"  title="未报名"  checked>
                      <input type="checkbox"  title="已报名" disabled="disabled">
                      {{# }if(d[key]['identity']==2){ }}
                      <input type="checkbox"  title="未报名"  disabled="disabled">
                      <input type="checkbox"  title="已报名" checked ">
                      {{# }if(d[key]['identity']==0){ }}
                      谁都看不见~~~
                      {{# } }}

                    </div>
                  </div>
                  <div class=" layui-col-md1">
                    <div class="content-text">
                      <input type="checkbox" lay-filter="content-switch" name="status" lay-skin="switch" lay-text="ON|OFF" {{ d[key]['status']==1?'checked':'' }} value="{{ d[key]['id'] }}">
                    </div>
                  </div>
                  <div class=" layui-col-md2">
                    <div style="text-align: right;">
                        <a class="layui-btn layui-default-update" href="<?= yii\helpers\Url::to(['content/update']); ?>?id={{ d[key]['id'] }}">编辑</a>
                    </div>
                  </div>
                </div>
                <div class="layui-row grid-content">
                    <div class="layui-col-md12">
                      <div class="content-text">
                        <label class="layui-form-label">主要内容：</label><div class="layui-input-block layui-bg-gray">{{ d[key]['content'] }}</div>
                      </div>
                    </div>
                </div>
                <div class="layui-row layui-col-space10 grid-content">
                  <div class=" layui-col-md3">
                    <div class="content-text">
                      <label class="layui-form-label">链接类型：</label><div class="layui-input-block ">{{ linkType(d[key]['link_type']) }}</div>
                    </div>
                  </div>
                  <div class=" layui-col-md4">
                    <div class="content-text layui-elip">
                      <label class="layui-form-label">链接地址：</label><a target="view_window" href="{{ d[key]['link'] }}">{{ d[key]['link'] }}</a>
                    </div>
                  </div>
                  <div class=" layui-col-md5">
                    <div class="content-text" style="color: #d2d2d2;text-align: right;">
                      创建时间：{{ timestampToTime(d[key]['created_at']) }}&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp最近更新时间：{{ timestampToTime(d[key]['updated_at']) }}
                    </div>
                  </div>
                </div>
            <div class="layui-row layui-col-space10 grid-content">
              {{# var inform =d[key]['inform']; Object.keys(inform).forEach(function(key){ }}
                <div class="layui-col-md3" >
                        <div class="{{ informStatus(inform[key]['processed'],inform[key]['star_time']) }} grid-content">
                          <div class="content-text">
                            <label style="width: 80px;">ID：</label>{{ inform[key]['id'] }}
                          </div>
                          <div class="content-text">
                            <label style="width: 80px;">标题：</label>{{ inform[key]['title'] }}
                          </div>
                          <div class="content-text {{ informTime(inform[key]['star_time']) }}">
                            <label style="width: 80px;">通知时间：</label>{{ timestampToTime(inform[key]['star_time']) }}
                          </div>
                          {{# var keywords = inform[key]['inform_doc']; var keyword = keywords.split(";"); }}
                          <div class="content-text">                            
                            <label style="width: 80px;">first：</label>{{ keyword[0] }}                              
                          </div>
                          <div class="content-text">                              
                            <label style="width: 80px;">keyword1：</label>{{ keyword[1] }}       
                          </div>
                          <div class="content-text">   
                              <label style="width: 80px;">keyword2：</label>{{ keyword[2] }}
                          </div>
                          <div class="content-text">
                            <label style="width: 80px;">keyword3：</label>{{ keyword[3] }}
                          </div>
                          <div class="content-text">
                            <label style="width: 80px;">remark：</label>{{ keyword[4] }}
                          </div>
                          <div class="content-text" style="font-size: 12px;color: #d2d2d2">
                            <label style="width: 80px;">创建时间：</label>{{ timestampToTime(inform[key]['created_at']) }}
                          </div>
                          <div class="content-text" style="font-size: 12px;color: #d2d2d2">
                            <label style="width: 80px;">更新时间：</label>{{ timestampToTime(inform[key]['updated_at']) }}
                          </div>
                          {{# if(inform[key]['processed']==0){ }}
                          <button class="layui-btn layui-inform-update" lay-submit lay-filter="formDemo" href="<?= yii\helpers\Url::to(['inform/update']); ?>?id={{ inform[key]['id'] }}">编辑</button>
                          {{# } if(inform[key]['processed']==1){ }}
                          <button class="layui-btn layui-btn-disabled"  disabled="disabled" lay-filter="formDemo"><i data="{{ inform[key]['processed_total'] }}" class="layui-icon layui-icon-log">发送中</i></button>
                          {{# } if(inform[key]['processed']==2){ }}
                          <button class="layui-btn layui-btn-disabled"  disabled="disabled" lay-filter="formDemo"><i data="{{ inform[key]['processed_total'] }}" class="layui-icon layui-icon-log">已发送</i></button>
                          {{# } }}
                          <button class="layui-btn layui-inform-send" lay-filter="formDemo" data="{{ inform[key]['inform_doc'] }}" href="<?= yii\helpers\Url::to(['inform/send']); ?>">通知测试</button>
                          <button class="layui-btn layui-btn-sm layui-btn-primary layui-inform-delete" href="<?= yii\helpers\Url::to(['inform/delete']); ?>?id={{ inform[key]['id'] }}" lay-filter="formDemo"><i class="layui-icon">&#xe640;</i></button>

                      </div>
              </div>
              {{# }); }}
              <div class="layui-col-md3" >
                <div class="content add-info" href="<?= yii\helpers\Url::to(['inform/create']); ?>?id={{ d[key]['id'] }}">
                    <div>
                        <i class="layui-icon">&#xe654;</i>
                        添加通知
                    </div>
                </div>
              </div>
            </div>
        </div>
        {{# }); }}
        {{# }else{ }}
        <div style="text-align:center;" ><i class="layui-icon layui-icon-face-cry"></i>暂无内容，请添加~</div>
        {{# } }}
  </script>
  </div> 
    <div class="layui-col-md12" style="margin-top:10px;margin-left: 10px;">
        <a  class="layui-btn  layui-btn-lg layui-content-add">+添加内容</a>
        <a  class="layui-btn  layui-btn-lg layui-btn-normal">内容预览</a>
    </div>             
</div>
<!-- 编辑省份链接 -->
  <div id="link-form" class="layui-hide" style="padding:20px;">
    <form class="layui-form" lay-filter="link-form">
      <div class="layui-form-item">
        <label class="layui-form-label">链接地址</label>
        <div class="layui-input-block">
          <input type="text" name="link" class="layui-input" value="<?= $model->link;?>">
        </div>
        <input type="text" name="id" class="layui-input layui-hide" value="<?= $model->id;?>">
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
            <div align='right'>
            <button class="layui-btn" lay-submit lay-filter="go">编辑</button>
            </div>
        </div>
      </div>
    </form>
  </div>
<!-- 编辑省份笔记 -->
  <div class="layui-form layui-hide province-remark-form" lay-filter="province-remark-form" style="padding:20px;">
      <div class="layui-form-item">
        <label class="layui-form-label">笔记</label>
        <div class="layui-input-block">
          <textarea name="remark" id="remark-edit" placeholder="请输入内容"  class="layui-textarea"><?= $model->remark; ?></textarea>
        </div>
        <input type="text" name="id" class="layui-input layui-hide" value="<?= $model->id;?>">
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
            <div align='right'>
            <button class="layui-btn" lay-submit lay-filter="province-remark-go">编辑</button>
            </div>
        </div>
      </div>
  </div>
<!-- 处理公告备注 -->
  <div style="padding:20px;" class="layui-form remark-form layui-hide" lay-filter="remark-form">
      <div class="layui-form-item">
        <label class="layui-form-label"><font color="red">*</font>选择备注</label>
        <div class="layui-input-block">
          <select name="remark" lay-filter="get-remark" lay-verify="required">
            <option value="">请选择或填写公告处理备注</option>
            <option value="无内容需要处理">无内容需要处理</option>
            <option value="无内容整理，但需要通知给用户，已建立通知项">无内容整理，但需要通知给用户，已建立通知项</option>
            <option value="已整理对应内容毕业计划，同时建立对应通知项">已整理对应内容计划，同时建立对应通知项</option>
            <option value="5">其他</option>
          </select>
        </div>
      </div>
      <div class="layui-form-item" style="display: none;" id="desc">
        <label class="layui-form-label">填写备注</label>
        <div class="layui-input-block">
          <textarea name="desc" placeholder="请输入内容"  class="layui-textarea"></textarea>
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
            <div align='right'>
              <button class="layui-btn" lay-submit lay-filter="remark-go">确认处理并提交</button>
            </div>
        </div>
      </div>
  </div>


