<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property int $type
 * @property int $task_id
 * @property int $user_id
 * @property int $created_at
 * @property int $active
 *
 * @property NotificationsTypes $type0
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'task_id', 'user_id', 'created_at', 'active'], 'required'],
            [['type', 'task_id', 'user_id', 'created_at', 'active'], 'integer'],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => NotificationsTypes::className(), 'targetAttribute' => ['type' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'active' => 'Active',
        ];
    }

    /**
     * Gets query for [[Type]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNotificationType()
    {
        return $this->hasOne(NotificationsTypes::className(), ['id' => 'type']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id'])
            ;
    }

    public function setNotification($arr) {
        $this->type = $arr['type'];
        $this->task_id = $arr['task_id'];
        $this->user_id = $arr['user_id'];
        $this->created_at = time();
        $this->active = 1;
        $this->save();
    }
}
