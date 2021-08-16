<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "responds".
 *
 * @property int $id
 * @property int|null $task_id
 * @property int|null $user_id
 * @property float|null $price
 * @property string|null $comment
 * @property int|null $created_at
 * @property int|null $is_accepted
 *
 * @property Users $user
 * @property Tasks $task
 */
class Respond extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'responds';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'task_id', 'user_id', 'created_at', 'is_accepted'], 'integer'],
            [['price'], 'number'],
            [['comment'], 'string'],
            [['id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
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
            'user_id' => 'User ID',
            'price' => 'Price',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'is_accepted' => 'Is Accepted',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
      * Обработчик создания отклика
      * @param array $fields
    */
    public function createRespond($fields) 
    {
        if (!empty($fields)) {
            $this->task_id = $fields['task_id'] ?? 0;
            $this->user_id = \Yii::$app->user->identity->id;
            $this->price = $fields['ResponseForm']['price'] ?? 0;
            $this->comment = $fields['ResponseForm']['comment'] ?? "";
            $this->created_at = strtotime('now');
            $this->is_accepted = null;

            if ($this->save()) {
                return true;
            }
        }

        return false;
    }

    /**
      * Обработчик обновления статуса отклика (принят или отклонен)
      * @param int $status
    */
    public function updateStatus($status)
    {
        $this->is_accepted = $status;
        $this->save();
    }
}
