<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "favourites".
 *
 * @property int $id
 * @property int|null $user_added
 * @property int|null $user_favourite
 * @property int|null $created_at
 *
 * @property Users $userAdded
 * @property Users $userFavourite
 */
class Favourite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favourites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'user_added', 'user_favourite', 'created_at'], 'integer'],
            [['id'], 'unique'],
            [['user_added'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_added' => 'id']],
            [['user_favourite'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_favourite' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_added' => 'User Added',
            'user_favourite' => 'User Favourite',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[UserAdded]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAdded()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_added']);
    }

    /**
     * Gets query for [[UserFavourite]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavourite()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_favourite']);
    }
}
