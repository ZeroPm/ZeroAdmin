<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "c_isread".
 *
 * @property int $id
 * @property string $union_id 用户唯一标识unionid
 * @property int $announcement_id 公告ID
 * @property int $isready 是否已读 0：未读 1：已读
 * @property int $created_at 创建时间
 */
class Isread extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'c_isread';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['openid', 'announcement_id', 'created_at'], 'required'],
            [['announcement_id', 'isready', 'created_at'], 'integer'],
            [['openid'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'openid' => '小程序openid',
            'announcement_id' => 'Announcement ID',
            'isready' => 'Isready',
            'created_at' => 'Created At',
        ];
    }
}
