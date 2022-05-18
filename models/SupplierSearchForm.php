<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Supplier;
use yii\helpers\VarDumper;

/**
 * SupplierSearchForm represents the model behind the search form of `app\models\Supplier`.
 */
class SupplierSearchForm extends Supplier
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'match', 'pattern' => '/^(>|<|>=|<=)*\d+$/'],
            [['name', 'code', 'status'], 'safe'],
            ['status', 'in', 'range' => ['hold', 'ok']],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Supplier::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                't_status',
                'code',
                'status' => [
                    'asc' => ['t_status' => SORT_ASC],
                    'desc' => ['t_status' => SORT_DESC],
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if (!empty($this->id)) {
            preg_match('/^(>|<|>=|<=)*(\d+)$/', $this->id, $matches);
            list(, $operate, $value) = $matches;
            if (empty($operate)) {
                $operate = '=';
            }
            $query->andWhere([
                $operate, 'id', $value
            ]);
        }

        $query->andFilterWhere([
            't_status' => $this->t_status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
