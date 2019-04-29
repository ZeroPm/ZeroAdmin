<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "c_wuser".
 *
 * @property int $id
 * @property string $openid 服务号openid
 * @property string $unionid
 * @property string $nickname 昵称
 * @property int $sex 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
 * @property string $headimgurl 用户头像
 * @property int $subscribe 是否关注
 * @property int $subscribe_time 用户关注时间
 * @property string $remark 公众号运营者对粉丝的备注
 * @property int $groupid 用户所在的分组ID
 * @property string $tagid_list 用户被打上的标签ID列表
 * @property string $subscribe_scene 关注的渠道来源
 * @property string $qr_scene 二维码扫码场景
 * @property string $qr_scene_str 二维码扫码场景描述
 */
class Wuser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'c_wuser';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['openid'], 'required'],
            [['sex', 'subscribe', 'subscribe_time', 'groupid'], 'integer'],
            //[['openid', 'unionid', 'headimgurl', 'remark', 'tagid_list', 'subscribe_scene', 'qr_scene_str'], 'string', 'max' => 255],
            [['nickname'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => 'Openid',
            'unionid' => 'Unionid',
            'nickname' => '昵称',
            'sex' => '性别',
            'headimgurl' => '头像',
            'subscribe' => '是否关注',
            'subscribe_time' => '关注时间',
            'remark' => '备注',
            'groupid' => '分组',
            'tagid_list' => '标签ID列表',
            // 返回用户关注的渠道来源，ADD_SCENE_SEARCH 公众号搜索，ADD_SCENE_ACCOUNT_MIGRATION 公众号迁移，ADD_SCENE_PROFILE_CARD 名片分享，ADD_SCENE_QR_CODE 扫描二维码，ADD_SCENEPROFILE LINK 图文页内名称点击，ADD_SCENE_PROFILE_ITEM 图文页右上角菜单，ADD_SCENE_PAID 支付后关注，ADD_SCENE_OTHERS 其他
            'subscribe_scene' => '关注场景',
            'qr_scene' => '二维码场景',
            'qr_scene_str' => '二维码场景描述',
            'created_at' => '创建时间',
            'updated_at' => '最新更新时间',
        ];
    }

    public static function findByOpenid($openid)
    {
        return Wuser::findOne(['openid' => $openid]);
    }

    public static function findByUnionid($unionid)
    {
        return Wuser::findOne(['unionid' => $unionid]);
    }
}
