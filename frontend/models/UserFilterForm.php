<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

use frontend\models\Category as Category;
use frontend\models\WorkType as WorkType;

class UserFilterForm extends Model
{
    /**
     * {@inheritdoc}
     */
    public $category, $worktype, $sQuery;
    public function rules()
    {
        return [
            [['category'], 'integer'],
            [['worktype'], 'integer'],
            [['sQuery'], 'unique'],
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->category = Category::find()->all();
    } 

    public function getWorkType()
    {
        return $this->worktype = WorkType::find()->all();
    }
}
