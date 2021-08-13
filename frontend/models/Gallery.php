<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "gallery".
 *
 * @property int $id
 * @property int|null $post_id
 * @property string|null $post_type
 * @property string|null $link
 * @property int|null $user_id
 * @property int|null $created_at
 *
 * @property Users $user
 * @property Tasks $post
 */
class Gallery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gallery_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'post_id', 'user_id', 'created_at'], 'integer'],
            [['post_type'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'post_type' => 'Post Type',
            'link' => 'Link',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
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
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Task::className(), ['id' => 'item_id']);
    }
}
