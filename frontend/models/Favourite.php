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
            [['id', 'user_added', 'user_favourite', 'created_at'], 'integer'],
            [['id'], 'unique'],
            [['user_added'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_added' => 'id']],
            [['user_favourite'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_favourite' => 'id']],
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
        return $this->hasOne(User::className(), ['id' => 'user_added']);
    }

    /**
     * Gets query for [[UserFavourite]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavourite()
    {
        return $this->hasOne(User::className(), ['id' => 'user_favourite']);
    }

    /**
      * Обработчик создания записи об избранном пользователе
      * @param array $args
    */
    public function createFavourite($args)
    {
        if (!empty($args)) {
            $this->user_added = $args['user_added'];
            $this->user_favourite = $args['user_favourite'];
            $this->created_at = $args['created_at'];

            if ($this->save()) {
                return true;
            }
        }

        return false;
    }
}
