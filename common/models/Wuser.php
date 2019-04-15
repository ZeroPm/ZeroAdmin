<?php

namespace common\models;

use Yii;

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['openid'], 'required'],
            [['sex', 'subscribe', 'subscribe_time', 'groupid'], 'integer'],
            [['openid', 'unionid', 'headimgurl', 'remark', 'tagid_list', 'subscribe_scene', 'qr_scene', 'qr_scene_str'], 'string', 'max' => 255],
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
            'subscribe_scene' => '关注场景',
            'qr_scene' => '二维码场景',
            'qr_scene_str' => '二维码场景描述',
        ];
    }
}
