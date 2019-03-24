<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "c_user".
 *
 * @property int $id 系统内唯一标识
 * @property string $uuid 用户加密ID
 * @property string $union_id 微信基于用户的唯一unionid
 * @property string $nickname 微信用户昵称
 * @property string $avatarurl 微信用户头像
 * @property string $gender 微信用户性别 1男 2女 0未知
 * @property string $isfollow 是否关注 0未关注；1已关注
 * @property int $created_at 注册时间
 * @property int $updated_at 最近活跃时间
 * @property string $wopenid 小白自考服务号openid
 * @property string $mopenid
 * @property string $parent_uuid 推荐人uuid，用作后续分享使用
 */
class Cuser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'c_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['union_id', 'created_at', 'updated_at', 'parent_uuid'], 'required'],
            [['isfollow', 'created_at', 'updated_at'], 'integer'],
            [['uuid', 'union_id', 'nickname', 'avatarurl', 'gender', 'wopenid', 'mopenid', 'parent_uuid'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'union_id' => 'Union ID',
            'nickname' => 'Nickname',
            'avatarurl' => 'Avatarurl',
            'gender' => 'Gender',
            'isfollow' => 'Isfollow',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'wopenid' => 'Wopenid',
            'mopenid' => 'Mopenid',
            'parent_uuid' => 'Parent Uuid',
        ];
    }
}
