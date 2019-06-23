<?php

namespace common\models;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;

use Yii;

/**
 * This is the model class for table "b_brand".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $img_src 图片地址
 * @property int $sort 排序
 * @property string $link 链接地址
 * @property int $status 状态0：关闭 1：开启 -1：已删除
 * @property int $create_time 创建时间
 * @property int $update_time 最近更新时间
 */
class Brand extends \yii\db\ActiveRecord
{

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELECT = -1;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_brand';
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
            [[ 'title', 'img_src', 'sort', 'link'], 'required'],
            [['sort', 'status', 'created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            [['title'], 'string', 'max' => 6],
            [['img_src', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'img_src' => '图片',
            'sort' => '排序',
            'link' => '链接地址',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '最近更新时间',
        ];
    }
    
     /**
     * 获取类别的下拉菜单
     * @return type
     */
    // public static function dropDown(){
    //     $data = self::find()->asArray()->all();
    //     $data_list = ArrayHelper::map($data, 'id', 'name');
    //     return $data_list;
    // } 
}
