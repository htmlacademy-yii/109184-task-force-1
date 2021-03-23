<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

use frontend\models\Category as Category;
use frontend\models\WorkType as WorkType;

class TaskFilterForm extends Model
{
    /**
     * {@inheritdoc}
     */
    public $category, $worktype, $period, $sQuery;
    public function rules()
    {
        return [
            [['category'], 'integer'],
            [['worktype'], 'integer'],
            [['period'], 'string', 'max' => 255],
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

    public function getPeriod()
    {
        return $this->period = ["day" => "За день", "week" => "За неделю", "month" => "За месяц"];
    }
}
