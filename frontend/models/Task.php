<?php

namespace frontend\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $category_id
 * @property int|null $work_type_id
 * @property int|null $city_id
 * @property float|null $price
 * @property int|null $expire_date
 * @property int|null $user_created
 * @property int|null $status
 * @property int|null $created_at
 *
 * @property Gallery[] $galleries
 * @property Messages[] $messages
 * @property Responds[] $responds
 * @property TaskActions[] $taskActions
 * @property Users $userCreated
 * @property Categories $category
 * @property WorkTypes $workType
 * @property Cities $city
 * @property TaskStatuses $status0
 */
class Task extends \yii\db\ActiveRecord
{
    public $filesUpload;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'work_type_id', 'city_id', 'user_created', 'status', 'created_at'], 'integer'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['title'], 'string', 'max' => 255],
            ['title', 'required', 'message' => 'Поле "Мне нужно" должно быть заполнено'],
            ['description', 'required', 'message' => 'Поле "Подробности задания" должно быть заполнено'],
            ['price', 'required', 'message' => 'Поле "Бюджет" должно быть заполнено'],
            ['price', 'compare', 'compareValue' => 0, 'operator' => '>=', 'type' => 'number', 'message' => 'Поле "Бюджет" должно быть положительным числом'],
            ['expire_date', 'required', 'message' => 'Поле "Сроки исполнения" должно быть заполнено'],
            [['id'], 'unique'],
            [['user_created'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_created' => 'id']],
            ['category_id', 'required', 'message' => 'Поле "Категория" должно быть заполнено'],
            [['category_id'], 'exist', 'skipOnError' => false, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['work_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkType::className(), 'targetAttribute' => ['work_type_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => TaskStatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['filesUpload'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Краткое название',
            'description' => 'Подробности задания',
            'category_id' => 'Категория',
            'work_type_id' => 'Work Type ID',
            'city_id' => 'Локация',
            'price' => 'Бюджет',
            'expire_date' => 'Сроки исполнения',
            'user_created' => 'User Created',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Galleries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasMany(Gallery::className(), ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(Responds::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskActions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskActions()
    {
        return $this->hasMany(TaskActions::className(), ['task_id' => 'id']);
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
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * Gets query for [[WorkType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkType()
    {
        return $this->hasOne(WorkTypes::className(), ['id' => 'work_type_id']);
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

    /**
     * Gets query for [[Status0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(TaskStatuses::className(), ['id' => 'status']);
    }
}
