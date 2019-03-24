<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "b_inform".
 *
 * @property int $id
 * @property int $content_id 关联内容ID
 * @property string $title 通知标题，后台使用
 * @property int $star_time 通知时间
 * @property string $inform_doc 通知内容 josn形式存储
 * @property int $status 0：已删除，1：有效
 * @property int $created_at 数据创建时间
 * @property int $updated_at 数据最近编辑时间
 */
class Inform extends \yii\db\ActiveRecord
{

    const STATUS_ISDELETE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_inform';
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
            [['content_id', 'star_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'inform_doc','star_time'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['inform_doc'], 'string', 'max' => 2048],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content_id' => '内容 ID',
            'title' => '标题（后台使用）',
            'star_time' => '通知时间',
            'inform_doc' => '通知内容',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
