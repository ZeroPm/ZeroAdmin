<?php

namespace common\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Cuser as CuserModel;

/**
 * Cuser represents the model behind the search form about `common\models\Cuser`.
 */
class Cuser extends CuserModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'isfollow', 'created_at', 'updated_at'], 'integer'],
            [['uuid', 'union_id', 'nickname', 'avatarurl', 'gender', 'wopenid', 'mopenid', 'parent_uuid'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CuserModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'isfollow' => $this->isfollow,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'union_id', $this->union_id])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'avatarurl', $this->avatarurl])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'wopenid', $this->wopenid])
            ->andFilterWhere(['like', 'mopenid', $this->mopenid])
            ->andFilterWhere(['like', 'parent_uuid', $this->parent_uuid]);

        return $dataProvider;
    }
}
