<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property int|null $user_created
 * @property string|null $text
 * @property int|null $user_reciever
 * @property float|null $rate
 * @property int|null $created_at
 *
 * @property Users $userCreated
 * @property Users $userReciever
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'user_created', 'user_reciever', 'created_at'], 'integer'],
            [['text'], 'string'],
            [['rate'], 'number'],
            [['id'], 'unique'],
            [['user_created'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_created' => 'id']],
            [['user_reciever'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_reciever' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_created' => 'User Created',
            'text' => 'Text',
            'user_reciever' => 'User Reciever',
            'rate' => 'Rate',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[UserCreated]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_created']);
    }

    /**
     * Gets query for [[UserReciever]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserReciever()
    {
        return $this->hasOne(User::className(), ['id' => 'user_reciever']);
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}
