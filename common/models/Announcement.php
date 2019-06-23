<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\data\Pagination;
use yii\base\Model;
use common\models\Isread;


/**
 * This is the model class for table "b_announcement".
 *
 * @property int $id
 * @property int $province_id 公告所属省份
 * @property string $title 公告标题
 * @property string $link 公告链接地址
 * @property string $date 公告时间，即省自考办发布公告的时间
 * @property int $status 处理状态   0：未处理  1：已处理 2：已删除
 * @property int $identity 通知对象  0：无通知对象 1：只通知未报名 2：只通知已报名 3：通知所有
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Announcement extends \yii\db\ActiveRecord
{

    const STATUS_UNPROCESSED = 0;
    const STATUS_PROCESSED = 1;
    const STATUS_DELETED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'b_announcement';
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function getIsread()
    {
        return $this->hasMany(Isread::className(), ['announcement_id'=>'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['province_id', 'status', 'identity', 'created_at', 'updated_at'], 'integer'],
            [['province_id','title', 'link', 'date'], 'required'],
            [['link'], 'string'],
            [['date'], 'safe'],
            [['title', 'remark'], 'string', 'max' => 255],
            ['status', 'default', 'value' => self::STATUS_UNPROCESSED],
            //indentity字段暂时未使用到，公告的通知等待后续微信规则，和用户的反馈。
            ['identity', 'default', 'value' => self::STATUS_UNPROCESSED],
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
            'title' => '标题',
            'link' => '链接',
            'date' => '发布日期',
            'status' => '状态',
            'identity' => '通知对象',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public static function getItems($cond,$limit,$pager)
    {

        $items = self::find()->where($cond)->offset($pager->offset)->limit($limit)->orderBy('date DESC')->all();

        return $items;
    }

    public static function getCount($cond)
    {

        $count = self::find()->where($cond)->count();

        return $count;

    }

    public static function getCreate($cond)
    {

        $create = self::find()->select('created_at')->where($cond)->orderBy('created_at ASC')->one();
        // if($create){
        //     return $create;
        // }
        return $create ? $create : '';
    }
}
