<?php

namespace api\modules\v1\controllers;

use yii;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;

class ArticleController extends ActiveController
{	
    public $modelClass = 'common\models\UserRank';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
	
    public function behaviors() {
        return ArrayHelper::merge (parent::behaviors(), [ 
                'authenticator' => [ 
                    'class' => QueryParamAuth::className()
                ] 
        ] );
    }
}
