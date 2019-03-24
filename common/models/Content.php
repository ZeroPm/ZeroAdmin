<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\Inform;

/**
 * This is the model class for table "b_content".
 *
 * @property int $id
 * @property int $province_id 内容所属省份
 * @property string $title 内容标题
 * @property string $content 富文本内容
 * @property int $sort 排序权重，越大越前
 * @property string $link 内容链接
 * @property int $link_type 链接类型 0：自考文章，1：公众号文章
 * @property int $status 0：关闭 1：开启中
 * @property int $identity 内容所属身份  1：未报名 2：已报名 3：所有
 * @property int $created_at
 * @property int $updated_at
 */
class Content extends \yii\db\ActiveRecord
{
    
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_content';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getInform()
    {
        return $this->hasMany(Inform::className(), ['content_id'=>'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province_id', 'title', 'content', 'sort', 'link', 'identity'], 'required'],
            [['province_id', 'sort', 'link_type', 'status', 'identity', 'created_at', 'updated_at'], 'integer'],
            [['content', 'link'], 'string'],
            [['title'], 'string', 'max' => 128],
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
            'province_id' => '省份ID',
            'title' => '内容标题',
            'content' => '主要内容',
            'sort' => '排序权重（越大越前）',
            'link' => '链接',
            'link_type' => '链接类型',
            'status' => '状态',
            'identity' => '所属群体',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static function findModel($id)
    {
        if (($model = Content::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
