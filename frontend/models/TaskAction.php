<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "task_actions".
 *
 * @property int $id
 * @property int|null $task_id
 * @property int|null $action_id
 *
 * @property Tasks $task
 * @property ActionsList $action
 */
class TaskAction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_actions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'task_id', 'action_id'], 'integer'],
            [['id'], 'unique'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['action_id'], 'exist', 'skipOnError' => true, 'targetClass' => ActionsList::className(), 'targetAttribute' => ['action_id' => 'id']],
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
            'action_id' => 'Action ID',
        ];
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

    /**
     * Gets query for [[Action]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAction()
    {
        return $this->hasOne(ActionsList::className(), ['id' => 'action_id']);
    }
}
