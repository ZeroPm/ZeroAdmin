<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;
use common\models\Content;

/**
 * This is the model class for table "b_province".
 *
 * @property int $id
 * @property int $province_id 省份ID
 * @property string $name 省份简称
 * @property string $fullname 省份全称
 * @property string $pinyin 省份拼音
 * @property string $cidx 子级行政区划在下级数组中的下标位置
 * @property string $location 中心点坐标
 * @property string $link 省自考办链接
 * @property int $status 省份是否开启 0关闭；1开启
 * @property int $created_at
 * @property int $updated_at
 */
class Province extends \yii\db\ActiveRecord
{
   
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_province';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getContent()
    {
        return $this->hasMany(Content::className(), ['province_id'=>'province_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province_id', 'name', 'fullname', 'pinyin', 'cidx', 'location', 'link'], 'required'],
            [['province_id', 'status', 'created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            //['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            [['link'], 'string'],
            [['name', 'fullname'], 'string', 'max' => 64],
            [['pinyin', 'cidx', 'location'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_id' => '省份ID',
            'name' => '省名',
            'fullname' => '省全名',
            'pinyin' => '拼音',
            'cidx' => '子级行政区划在下级数组中的下标位置',
            'location' => '中心点坐标',
            'link' => '省自考办链接',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static  function findModel($id)
    {
        if (($model = Province::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public static function findName($name)
    {
        //return $name;
        // if (($model = Province::findOne(['fullname' => $name])) !== null) {
        //     return $model;
        // } else {
        //     //api项目需要调取的方法不能这么写
        //     //throw new NotFoundHttpException('The requested page does not exist.');
        //     return false;
        // }
        return Province::findOne(['fullname' => $name]);
    }


}
