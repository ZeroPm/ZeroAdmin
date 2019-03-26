<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;

if($model->gender==1){
    $model->gender = '<font color="blue">男</font>';
}else if($model->gender==2){
    $model->gender = '<font color="red">女</font>';
}else{
    $model->gender = '未知';
}
$this->registerJs($this->render('js/view.js'));
LayuiAsset::register($this);
?>
<style type="text/css">
    .content{
        border: 1px solid  #e6e6e6;
        margin-bottom: 10px;
    }
    .content-text{
      line-height:38px;
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
</script>

<div class="layui-form ">

    <blockquote class="layui-elem-quote layui-quote-nm">
    用户基础资料
    </blockquote>
    <!-- 基础资料 -->
    <div class="content">
        <div class="layui-row layui-fluid" style="margin-bottom: 10px; background-color: #e6e6e6;" >
            <div class="layui-col-md1">
                <div class="grid-basis content-text">
                    uuid
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis content-text">
                    union_id
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="grid-basis text-center content-text">
                   头像
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                   昵称
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                    性别
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                    是否关注
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                   业务概况
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="grid-basis text-center content-text">
                  创建时间 
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="grid-basis text-center content-text">
                  最近活跃时间 
                </div>
            </div>
        </div>
        <div class="layui-row layui-fluid" style="margin-bottom: 10px; ">
            <div class="layui-col-md1">
                <div class="grid-basis content-text">
                    <div class='uuid'><?= $model->uuid;?></div>
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis content-text">
                    <?= $model->union_id;?>
                </div>
            </div>
            <div class="layui-col-md2 ">
                <div class="grid-basis text-center content-text">
                   <img src="<?= $model->avatarurl;?>" width="30px" height="30px">
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                   <?= $model->nickname;?> 
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                    <?= $model->gender;?>
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                    <?= $model->isfollow==1 ? '<font color="green">已关注</font>' : '未关注';?>
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                   123
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="grid-basis text-center content-text">
                  <?= date('Y-m-d H:i:s', $model->created_at);?>  
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="grid-basis text-center content-text">
                  <?= date('Y-m-d H:i:s', $model->updated_at);?> 
                </div>
            </div>
        </div>
    </div>
    <div style="padding: 5px;">
    <div class="layui-row layui-col-space10" >
        <div class="layui-col-md6 layui-card" >
            <div class="layui-card-header">订阅情况</div>
            <div class="layui-col-md12 layui-card-body">
                    <div style="text-align:center;" class="layui-hide content-loading"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i>加载中</div>
                <ul class="layui-timeline" id="sub-operation-data">
                    <script type="text/html" id="suboperationTpl">
                    {{# if(d.length!=0){ }}
                    {{# Object.keys(d).forEach(function(key){}}
                      <li class="layui-timeline-item">
                        {{# if(key==0){ }}
                        <i class="layui-icon layui-timeline-axis"></i>
                        {{# }else{ }}
                        <i class="layui-icon layui-timeline-axis"></i>
                        {{# } }}
                        <div class="layui-timeline-content layui-text">
                          <div class="layui-timeline-title">{{timestampToTime(d[key]['created_at']) }}&nbsp&nbsp&nbsp{{ d[key]['province']['name'] }}&nbsp&nbsp&nbsp{{ d[key]['isub']==1?'未报名':'已报名' }}</div>
                        </div>
                      </li>
                    {{# }); }}
                    {{# }else{ }}
                    <div style="text-align:center;" ><i class="layui-icon layui-icon-face-cry"></i>暂无内容</div>
                    {{# } }}
                    </script>
                </ul>
            </div>
        </div>
        <div class="layui-col-md6 layui-card">
            <div class="layui-card-header">首页使用情况</div>
            <div class="layui-col-md12 layui-card-body">
                    <div style="text-align:center;" class="layui-hide content-loading"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i>加载中</div>
                <ul class="layui-timeline" id="first-operation-data">
                    <script type="text/html" id="firstoperationTpl" >
                    {{# if(d.length!=0){ }}
                    {{# Object.keys(d).forEach(function(key){}}
                      <li class="layui-timeline-item">
                        {{# if(key==0){ }}
                        <i class="layui-icon layui-timeline-axis"></i>
                        {{# }else{ }}
                        <i class="layui-icon layui-timeline-axis"></i>
                        {{# } }}
                        <div class="layui-timeline-content layui-text">
                          <div class="layui-timeline-title">{{timestampToTime(d[key]['created_at']) }}&nbsp&nbsp&nbsp{{ d[key]['province']['name'] }}</div>
                        </div>
                      </li>
                    {{# }); }}
                    {{# }else{ }}
                    <div style="text-align:center;" ><i class="layui-icon layui-icon-face-cry"></i>暂无内容</div>
                    {{# } }}
                    </script>
<!--                   <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis"></i>
                    <div class="layui-timeline-content layui-text">
                      <div class="layui-timeline-title">2017年，layui 里程碑版本 2.0 发布</div>
                    </div>
                  </li>
                  <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis"></i>
                    <div class="layui-timeline-content layui-text">
                      <div class="layui-timeline-title">2016年，layui 首个版本发布</div>
                    </div>
                  </li>
                  <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis"></i>
                    <div class="layui-timeline-content layui-text">
                      <div class="layui-timeline-title">2015年，layui 孵化</div>
                    </div>
                  </li>
                  <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis"></i>
                    <div class="layui-timeline-content layui-text">
                      <div class="layui-timeline-title">更久前，轮子时代。维护几个独立组件：layer等</div>
                    </div>
                  </li> -->
                </ul>
            </div>
        </div> 
    </div>
    </div>
</div>


<!-- <div class="cuser-view">
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

</div> -->
