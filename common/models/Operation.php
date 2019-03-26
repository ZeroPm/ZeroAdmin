<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\Province;

/**
 * This is the model class for table "c_operation".
 *
 * @property int $id 自增加ID
 * @property string $uuid 微信unionid
 * @property int $type 操作类型1：首页操作；2：订阅操作
 * @property int $province_id 省份ID
 * @property int $created_at 创建时间，即变更时间
 * @property int $isub 0 无 1：未报名 2：已报名
 */
class Operation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'c_operation';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

   public function getProvince()
    {
        return $this->hasOne(Province::className(), ['province_id'=>'province_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'province_id', 'created_at','isub'], 'required'],
            [['type', 'province_id', 'created_at','isub'], 'integer'],
            [['uuid'], 'string', 'max' => 255],
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
            'type' => 'Type',
            'province_id' => 'Province ID',
            'created_at' => 'Created At',
            'isub' => 'Isub',
        ];
    }
}
