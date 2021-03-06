<?php

namespace common\models;

use Yii;
use common\models\Province;
use yii\web\NotFoundHttpException;

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
            [['uuid', 'province_id','type'], 'required'],
            [['type', 'province_id', 'created_at','updated_at'], 'integer'],
            //暂时使用的Cuser表中的自增长ID。验证规则冲突。
            //[['uuid'], 'string', 'max' => 255],
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
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'isub' => 'Isub',
        ];
    }

    public static function isSub($id)
    {
        return Operation::findOne(['uuid' => $id,'type'=>2]);
    }

    public static function findById($id)
    {
        return Operation::findOne(['id' => $id]);
    }
}
