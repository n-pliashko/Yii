<?php
namespace app\models;

use yii\data\ActiveDataProvider;
use yii\db\Query;

class CustomerSearch extends Customers {

    public function search($params){
        $query = (new Query())
            ->from(Customers::tableName())
            ->indexBy('id')
            ->orderBy('created DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 5,
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created' => $this->created
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email]);
        return $dataProvider;
    }
}