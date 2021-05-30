<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "addresses".
 *
 * @property int $id
 * @property string $name
 * @property float $lat
 * @property float $long
 *
 * @property Tasks[] $tasks
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'addresses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'lat', 'long'], 'required'],
            [['name'], 'string'],
            [['lat', 'long'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'lat' => 'Lat',
            'long' => 'Long',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::className(), ['address_id' => 'id']);
    }

     /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id']);
    }  
}
