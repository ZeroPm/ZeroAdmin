<?php
use yii\widgets\DetailView;
use backend\assets\LayuiAsset;
use common\models\Operation;

$model->gender = $model->gender ? sex($model->gender): '未知';

$wuserSex = $model->wuser ? sex($model->wuser->sex): '未知';

function sex($sex){
    switch ($sex) {
    case 1:
        $sex = '<font color="blue">男</font>';
        break;
    case 2:
        $sex = '<font color="red">女</font>';
        break;
    default:
        $sex = '未知';
        break;
    }
    return $sex;
}
    $wuserOpenid = false;
    $wuserUnionid = false;
    $wuserNickname = false;
    $wuserHeadimgurl = false;
    $wuserSubscribe = false;
    $wuserQrscenestr = false;
    $wuserCreated = false;
    $wuserUpdated = false;
    $subscribe_scene = false;
if($model->wuser){
    $wuserOpenid = $model->wuser->openid;
    $wuserUnionid = $model->wuser->unionid;
    $wuserNickname = $model->wuser->nickname;
    $wuserHeadimgurl = $model->wuser->headimgurl;
    $wuserSubscribe = $model->wuser->subscribe;
    $wuserQrscenestr = $model->wuser->qr_scene_str;
    $wuserCreated = $model->wuser->created_at;
    $wuserUpdated = $model->wuser->updated_at;
    switch ($model->wuser->subscribe_scene) {
        case 'ADD_SCENE_SEARCH':
            $subscribe_scene = '公众号搜索';
            break;
        case 'ADD_SCENE_ACCOUNT_MIGRATION':
            $subscribe_scene = '公众号迁移';
            break;
        case 'ADD_SCENE_PROFILE_CARD':
            $subscribe_scene = '名片分享';
            break;
        case 'ADD_SCENE_QR_CODE':
            $subscribe_scene = '<font color="orange" class="scene-qr-code">扫描二维码</font>';
            break;
        case 'ADD_SCENEPROFILE LINK':
            $subscribe_scene = '图文页内名称点击';
            break;
        case 'ADD_SCENE_PROFILE_ITEM':
            $subscribe_scene = '图文页右上角菜单';
            break;
        case 'ADD_SCENE_PAID':
            $subscribe_scene = '支付后关注';
            break;
        case 'ADD_SCENE_OTHERS':
            $subscribe_scene = '其他';
            break;
        default:
            $subscribe_scene = '啥都不是';
            break;
    }
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
      var date = new Date(timestamp*1000);//如果date为13位不需要乘1000
      var Y = date.getFullYear() + '-';
      var M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
      var D = (date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate()) + ' ';
      var h = (date.getHours() < 10 ? '0' + date.getHours() : date.getHours()) + ':';
      var m = (date.getMinutes() <10 ? '0' + date.getMinutes() : date.getMinutes()) + ':';
      var s = (date.getSeconds() <10 ? '0' + date.getSeconds() : date.getSeconds());
      return Y+M+D+h+m+s;
  }
</script>

<div class="layui-form ">

    <blockquote class="layui-elem-quote layui-quote-nm">
    用户基础资料
    </blockquote>
    <!-- 基础资料 -->
    <div class="layui-row layui-fluid">
        <div class="layui-col-md12">
            <fieldset><legend><span style="font-size: 16px;">用户小程序信息</span></legend></fieldset>
        </div>
    </div>
    <div class="content">
        <div class="layui-row layui-fluid" style="margin-bottom: 10px; background-color: #e6e6e6;" >
            <div class="layui-col-md3">
                <div class="grid-basis content-text">
                    小程序opneid&&unionid
                </div>
            </div>
            <!-- <div class="layui-col-md1">
                <div class="grid-basis content-text">
                    union_id
                </div>
            </div> -->
            <div class="layui-col-md1">
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
            <div class="layui-col-md3">
                <div class="grid-basis content-text">
                    <div class='uuid'>
                        openid：<?= $model->mopenid?$model->mopenid:'&nbsp'; ?><br>
                        unionid：<?= $model->union_id?$model->union_id:'&nbsp';?>
                    </div>
                </div>
            </div>
            <!-- <div class="layui-col-md1">
                <div class="grid-basis content-text layui-elip">
                    <?= $model->union_id?$model->union_id:'&nbsp';?>
                </div>
            </div> -->
            <div class="layui-col-md1 ">
                <div class="grid-basis text-center content-text">
                   <img src="<?= $model->avatarurl;?>" width="30px" height="30px">
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                   <?= $model->nickname?$model->nickname:'&nbsp';?> 
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
                   <?= Operation::isSub($model->id) ? '<font color="green">已订阅</font>':'未订阅';?>
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
    <div class="layui-row layui-fluid">
        <div class="layui-col-md12">
            <fieldset><legend><span style="font-size: 16px;">用户服务号资料</span></legend></fieldset>
        </div>
    </div>
        <?php if($model->wuser){ ?>
        <div class="content">
        <div class="layui-row layui-fluid" style="margin-bottom: 10px; background-color: #e6e6e6;" >
            <div class="layui-col-md3">
                <div class="grid-basis content-text">
                    小程序opneid&&unionid
                </div>
            </div>
            <!-- <div class="layui-col-md2">
                <div class="grid-basis content-text">
                    union_id
                </div>
            </div> -->
            <div class="layui-col-md1">
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
                   关注来源
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
            <div class="layui-col-md3">
                <div class="grid-basis content-text">
                    <div class='uuid'>
                        openid：<?= $wuserOpenid?$wuserOpenid:'&nbsp'; ?><br>
                        unionid：<?= $wuserUnionid?$wuserUnionid:'&nbsp';?>   
                    </div>
                </div>
            </div>
            <!-- <div class="layui-col-md2">
                <div class="grid-basis content-text layui-elip">
                    <?= $wuserUnionid?$wuserUnionid:'&nbsp';?>
                </div>
            </div> -->
            <div class="layui-col-md1 ">
                <div class="grid-basis text-center content-text">
                   <img src="<?= $wuserHeadimgurl;?>" width="30px" height="30px">
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                   <?= $wuserNickname?$wuserNickname:'&nbsp';?> 
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                    <?= $wuserSex;?>
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                    <?= $wuserSubscribe==1 ? '<font color="green">已关注</font>' : '未关注';?>
                </div>
            </div>
            <div class="layui-col-md1">
                <div class="grid-basis text-center content-text">
                   <b data="<?= $wuserQrscenestr; ?>" id="code-str"><?= $subscribe_scene;?></b>
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="grid-basis text-center content-text">
                  <?= date('Y-m-d H:i:s', $wuserCreated);?>  
                </div>
            </div>
            <div class="layui-col-md2">
                <div class="grid-basis text-center content-text">
                  <?= date('Y-m-d H:i:s', $wuserUpdated);?> 
                </div>
            </div>
        </div>
    </div>
    <?php }else{ ?>
    <div class="content">
        <div class="layui-row layui-fluid" style="margin-bottom: 10px; background-color: #e6e6e6;" >
            <div class="layui-col-md3">
                <div class="grid-basis content-text">
                    小程序opneid&&unionid
                </div>
            </div>
            <!-- <div class="layui-col-md2">
                <div class="grid-basis content-text">
                    union_id
                </div>
            </div> -->
            <div class="layui-col-md1">
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
                   关注来源
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
            <div class="layui-col-md12">
                <div class="grid-basis content-text">
                    <div class='uuid'>
                        <?= "暂无服务号信息";?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- 用户业务数据 -->
    <blockquote class="layui-elem-quote layui-quote-nm">
    用户业务数据
    </blockquote>
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
                          <div class="layui-timeline-title">{{timestampToTime(d[key]['created_at']) }}&nbsp&nbsp&nbsp{{ d[key]['province']?d[key]['province']['name']:'未知有BUG' }}&nbsp&nbsp&nbsp{{ d[key]['isub']==1?'未报名':'已报名' }}</div>
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
                          <div class="layui-timeline-title">{{timestampToTime(d[key]['created_at']) }}&nbsp&nbsp&nbsp{{ d[key]['province']?d[key]['province']['name']:'未知有BUG' }}</div>
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


