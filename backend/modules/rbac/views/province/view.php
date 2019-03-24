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
        var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
        Y = date.getFullYear() + '-';
        M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        D = date.getDate() + ' ';
        h = date.getHours() + ':';
        m = date.getMinutes() + ':';
        s = date.getSeconds();
        return Y+M+D+h+m+s;
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
</script>
<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;
use yii\helpers\Html;

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
  <a href={{d.link}} style="color:#FFB800;" target="_blank">{{ d.title }}</a>
  {{#  } else { }}
  <a href={{d.link}} style="color:#666;" target="_blank">{{ d.title }}</a>
  {{#  } }}
</script>
<!-- 公告待处理数量及收录时间 -->
<script type="text/html" id="uncoutTpl">
    <span style="color: #FFB800">待处理{{d.uncount}}条</span>&nbsp&nbsp&nbsp&nbsp{{d.create}}
</script>

<div class="layui-form ">

    <blockquote class="layui-elem-quote layui-quote-nm">
    基础资料
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
                <div class="layui-elip layui-col-md9"><a target="view_window" href="<?= $model->link ?>"><?= $model->link ?></a></div>
                <div class="layui-col-md3"><i class="layui-icon layui-icon-edit" style="font-size: 25px;"></i></div>
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
            <div class="grid-basis">各项数据分析</div>
        </div>
    </div>

    <blockquote class="layui-elem-quote layui-quote-nm">
    公告
    <!-- 待处理及收录时间的容器 -->
    <span class="an-total" id="uncount">
    </span>
    </blockquote>
    <!-- 公告 -->
    <table id="ancontent" lay-filter="ancontent"></table>
    
    <blockquote class="layui-elem-quote layui-quote-nm">
    内容及通知
    </blockquote>
    <!-- 内容及通知 -->
    <div style="text-align:center;" class="layui-hide content-loading"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i></div>
    <div id="content-table">
    <script type="text/html" id='contentTpl'>
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
                  <div class=" layui-col-md2">
                    <div class="content-text">
                      <label class="layui-form-label">链接类型：</label><div class="layui-input-block ">{{ linkType(d[key]['link_type']) }}</div>
                    </div>
                  </div>
                  <div class=" layui-col-md5">
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
                        <div class="content grid-content">
                          <div class="content-text">
                            <label style="width: 80px;">标题：</label>{{ inform[key]['title'] }}
                          </div>
                          <div class="content-text">
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
                          <a class="layui-btn layui-inform-update" lay-submit lay-filter="formDemo" href="<?= yii\helpers\Url::to(['inform/update']); ?>?id={{ inform[key]['id'] }}">编辑</a>
                          <a class="layui-btn" lay-submit lay-filter="formDemo">通知测试</a>
                          <a class="layui-btn layui-btn-sm layui-btn-primary layui-inform-delete" href="<?= yii\helpers\Url::to(['inform/delete']); ?>?id={{ inform[key]['id'] }}" lay-filter="formDemo"><i class="layui-icon">&#xe640;</i></a>
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
  </script>
  </div> 
    <div class="layui-col-md12" style="margin-top:10px;margin-left: 10px;">
        <a  class="layui-btn  layui-btn-lg layui-content-add">+添加内容</a>
        <a  class="layui-btn  layui-btn-lg layui-btn-normal">内容预览</a>
    </div>             
</div>

<!-- <div class="province-view">
    <?= DetailView::widget([
        'model' => $model,
		'options' => ['class' => 'layui-table'],
		'template' => '<tr><th width="30%">{label}</th><td>{value}</td></tr>', 
        'attributes' => [
            'id',
            'province_id',
            'name',
            'fullname',
            'pinyin',
            'location',
            'cidx',
            'link:ntext',
            'status',
            [
                "attribute" => "created_at",
                "format" => ["date", "php:Y-m-d H:i:s"],
            ],
            [
                "attribute" => "updated_at",
                "format" => ["date", "php:Y-m-d H:i:s"],
            ],
        ],
    ]) ?>

</div> -->

