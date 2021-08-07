<?php

namespace frontend\models;

use Yii;
use frontend\models\Notification as Notification;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property int|null $task_id
 * @property int|null $user_from
 * @property int|null $user_to
 * @property string|null $text
 * @property int|null $status
 * @property int|null $created_at
 *
 * @property Users $userFrom
 * @property Users $userTo
 * @property Tasks $task
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'task_id', 'user_from', 'user_to', 'status', 'created_at'], 'integer'],
            [['text'], 'string'],
            [['id'], 'unique'],
            [['user_from'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_from' => 'id']],
            [['user_to'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_to' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['created_at'], 'default', 'value' => function ($model, $attribute) {
                return strtotime('now');
            }]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'user_from' => 'User From',
            'user_to' => 'User To',
            'text' => 'Text',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            (new Notification())->setNotification(['type' => 2, 'task_id' => $this->task_id, 'user_id' => \Yii::$app->user->identity->id ]);
        }
    }

    /**
     * Gets query for [[UserFrom]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserFrom()
    {
        return $this->hasOne(User::className(), ['id' => 'user_from']);
    }

    /**
     * Gets query for [[UserTo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserTo()
    {
        return $this->hasOne(User::className(), ['id' => 'user_to']);
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
