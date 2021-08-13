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
    const STATUS_NEW = 'new'; // Новое
    const STATUS_CANCELED = 'canceled'; // Отменено
    const STATUS_INWORK = 'inwork'; // В работе
    const STATUS_COMPLETE = 'complete'; // Выполнено
    const STATUS_FAILED = 'failed'; // Провалено

    const ACTION_PUBLISH = 'publish';
    const ACTION_CANCEL = 'cancel';
    const ACTION_CHOOSE = 'choose';
    const ACTION_MARK_DONE = 'request';
    const ACTION_REFUSE = 'refusal';
    const ACTION_RESPOND = 'respond';
    const ACTION_WRITE_MESSAGE = 'write_message';

    private $status = [];
    private $actions = [];

    private $actionsList = [
        'publish' => 'Опубликовать задание',
        'cancel' => 'Отменить задание',
        'choose' => 'Выбрать исполнителя',
        'request' => 'Завершить',
        'refusal' => 'Отказаться',
        'respond' => 'Откликнуться',
    ];

    public $statusesList = [
        'new' => 'Новое',
        'canceled' => 'Отменено',
        'inwork' => 'На исполнении',
        'complete' => 'Завершено',
        'failed' => 'Провалено',
    ];

    private $actionStatusList = [
        self::ACTION_PUBLISH => self::STATUS_NEW,
        self::ACTION_CANCEL => self::STATUS_CANCELED,
        self::ACTION_CHOOSE => self::STATUS_INWORK,
        self::ACTION_MARK_DONE => self::STATUS_COMPLETE,
        self::ACTION_REFUSE => self::STATUS_FAILED,
    ];

    private $actionStatusListByRole = [
        'executant' => [
            self::STATUS_NEW => [self::ACTION_RESPOND],
            self::STATUS_INWORK => [self::ACTION_REFUSE],
        ],
        'customer' => [
            self::STATUS_INWORK => [self::ACTION_MARK_DONE],
        ],
    ];

    public $filesUpload;
    public $addressText;
    public $position;

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
            [['address_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::className(), 'targetAttribute' => ['address_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => TaskStatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['filesUpload'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10, 'extensions' => 'png, jpg, jpeg'],
            [['addressText'], 'required', 'message' => 'Поле "Локация" должно быть заполнено'],
            [['addressText'], 'string', 'max' => 255],
            [['position'], 'string', 'max' => 255]

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
            'address_id' => 'Адрес',
            'price' => 'Бюджет',
            'expire_date' => 'Сроки исполнения',
            'user_created' => 'User Created',
            'status' => 'Status',
            'created_at' => 'Created At',
            'addressText' => 'Локация',
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
        return $this->hasMany(Message::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Responds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespond()
    {
        return $this->hasMany(Respond::className(), ['task_id' => 'id']);
    }

    /**
     * Gets query for [[TaskActions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTaskActions()
    {
        return $this->hasMany(TaskAction::className(), ['task_id' => 'id']);
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
        return $this->hasOne(WorkType::className(), ['id' => 'work_type_id']);
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
     * Gets query for [[Address]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    } 

    /**
     * Gets query for [[Status0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusName()
    {
        return $this->hasOne(TaskStatus::className(), ['id' => 'status']);
    }

    public function getExecutant() {
        return Respond::find()->andWhere(['task_id' => $this->id])->andWhere(['is_accepted' => '1'])->one();
    }

    public function getAvailableActionsByStatus($status, $role)
    {
        $UserRole = UserRole::findOne($role);
        $taskStatus = TaskStatus::findOne($status);

        return $this->actionStatusListByRole[$UserRole->role][$taskStatus->description] ?? [];
    }

    public function getActionName($action) {
        return $this->actionsList[$action];
    }

    public function checkRespond() {
        if (Respond::find()->where(['task_id' => $this->id])->andWhere(['user_id' => \Yii::$app->user->identity->id])->one()) {
            return true;
        }

        return false;
    }

    public function getDuration() {
        $mess = '';
        $userDate = new \DateTime(date('Y-m-d H:i:s', $this->user->created_at));
        $duration   = $userDate ->diff(new \DateTime('now'));
        
        if ($duration->y) {
            $laststr = 'лет';
            if (substr($duration->y, -1) == '1') $laststr = 'год';
            if (substr($duration->y, -1) == '2' || substr($duration->y, -1) == '3' || substr($duration->y, -1) == '4') $laststr = 'годa';

            $mess .= $duration->y . " $laststr ";
        }

        if ($duration->m) {
            $laststr = 'месяцев';
            if (substr($duration->m, -1) == '1') $laststr = 'месяц';
            if (substr($duration->m, -1) == '2' || substr($duration->m, -1) == '3' || substr($duration->m, -1) == '4') $laststr = 'месяца';

            $mess .= $duration->m . " $laststr ";
        }

        if ($duration->d) {
            $laststr = 'дней';
            if (substr($duration->d, -1) == '1') $laststr = 'день';
            if (substr($duration->d, -1) == '2' || substr($duration->d, -1) == '3' || substr($duration->d, -1) == '4') $laststr = 'дня';

            $mess .= $duration->d . " $laststr ";
        }

        if ($duration->h) {
            $laststr = 'часов';
            if (substr($duration->h, -1) == '1') $laststr = 'час';
            if (substr($duration->h, -1) == '2' || substr($duration->h, -1) == '3' || substr($duration->h, -1) == '4') $laststr = 'часа';

            $mess .= $duration->h . " $laststr ";
        }

        return $mess ?? 'несколько минут';
    }

}
