<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Correction;

/**
 * CorrectionSearch represents the model behind the search form of `app\models\Correction`.
 */
class CorrectionSearch extends Correction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'post_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text', 'reject_reason'], 'safe'],
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
        $query = Correction::find();

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
            //'post_id' => $this->post_id,
            //'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

    /*    $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'reject_reason', $this->reject_reason]);
*/
        return $dataProvider;
    }

    public function statusList()
    {
        return [Correction::STATUS_PENDING => "на модерации", Correction::STATUS_APPROVED => "одобрено", Correction::STATUS_REJECTED => "отклонено"];
    }
}
