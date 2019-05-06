<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\NotFoundHttpException;
use common\models\Operation;

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

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    //1对多获取数据
    public function getOperations()
    {
        return $this->hasMany(Operation::className(), ['uuid'=>'id']);
    }
    //只获取一条数据
    public function getOperation()
    {
        return $this->hasOne(Operation::className(), ['uuid'=>'id']);
    }



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['session_key'], 'required'],
            [['isfollow', 'created_at', 'updated_at', 'gender','isread','province_id','isub'], 'integer'],
            [['uuid', 'union_id', 'nickname', 'avatarurl', 'wopenid', 'mopenid', 'parent_uuid','session_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'uuid',
            'union_id' => 'Union ID',
            'nickname' => '昵称',
            'avatarurl' => '头像',
            'gender' => '性别',
            'isfollow' => '是否关注',
            'created_at' => '创建时间',
            'updated_at' => '最近活跃时间',
            'wopenid' => '公众号openid',
            'mopenid' => '小程序openid',
            'parent_uuid' => 'Parent Uuid',
            'session_key' => 'Session_key',
            'isread' => 'isread',
            'province_id' => '当前订阅省份',
            'isub' => '当前订阅身份',
        ];
    }

    //小程序openid查询
    public static function findByOpenidm($openid)
    {
        return Cuser::findOne(['mopenid' => $openid]);
    }

    //服务号openid查询
    public static function findByOpenidw($openid)
    {
        return Cuser::findOne(['wopenid' => $openid]);
    }

    //unionid查询
    public static function findByUnionid($unionid)
    {
        //return Cuser::findOne(['union_id' => $unionid]);

        return Cuser::find()->where(['union_id'=>$unionid])->with(
            ['operation'=>function($query){
                $cond = ['type'=>2];
                $query->andwhere($cond)->orderBy('created_at DESC');
                }
            ]
        )->one();
    }

    public static  function findIsubData($province_id)
    {
        $isubData = Yii::$app->memcache->get('isubData'.$province_id);
        if(!$isubData){
            $isubData = Cuser::find()->select('province_id,isub')->where(['province_id'=>$province_id])->asArray()->all();
            Yii::$app->memcache->set('isubData'.$province_id,$isubData,300);
        }
        return $isubData;
    }

}
