<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "actions_list".
 *
 * @property int $id
 * @property string|null $description
 *
 * @property TaskActions[] $taskActions
 */
class ActionList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actions_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['description'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[TaskActions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskActions()
    {
        return $this->hasMany(TaskActions::className(), ['action_id' => 'id']);
    }
}
