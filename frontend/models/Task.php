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

    private $statusTask = [];
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

    /**
      * Обработчик получения исполнителя задания
    */
    public function getExecutant() {
        return Respond::find()->andWhere(['task_id' => $this->id])->andWhere(['is_accepted' => '1'])->one();
    }

    /**
      * Обработчик получения доступных действий в зависимости от статуса задания
      * @param int $statusTask
      * @param int $role
    */
    public function getAvailableActionsByStatus($statusTask, $role)
    {
        $UserRole = UserRole::findOne($role);
        $taskStatus = TaskStatus::findOne($statusTask);

        return $this->actionStatusListByRole[$UserRole->role][$taskStatus->description] ?? [];
    }

    /**
      * Обработчик получения имени действия по айди
      * @param int $action
    */
    public function getActionName($action) 
    {
        return $this->actionsList[$action];
    }

    /**
      * Проверка на существование отклика на задание от пользователя
    */
    public function checkRespond() {
        if (Respond::find()->where(['task_id' => $this->id])->andWhere(['user_id' => \Yii::$app->user->identity->id])->one()) {
            return true;
        }

        return false;
    }

    /**
      * Кастомизация длительности времени для пользователя (срок регистрации)
    */
    public function getDuration() 
    {
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

    /**
      * Обработчик создания задания
      * @param array $fields
    */
    public function createTask($fields) 
    {
        if (!empty($fields)) {
            $this->title = $fields['Task']['title'];
            $this->description = $fields['Task']['description'];
            $this->category_id = $fields['Task']['category_id'];
            $this->price = $fields['Task']['price'];
            $this->expire_date = strtotime($fields['Task']['expire_date']);
            
            $this->user_created = \Yii::$app->user->identity->id;
            $this->created_at = strtotime('now');

            $city = City::find()->where(['name' => $fields['city']])->one();

            if (!$city) {
                $city = new City();
                $city->name = $fields['city'];
                $position = explode(" ", $fields['position']);
                $city->lat = $position[1];
                $city->long = $position[0];
                $city->save();
            }

            $address = Address::find()->where(['name' => $fields['Task']['addressText']])->one();
           
            if (!$address) {
                $address = new Address();
                $address->name = $fields['Task']['addressText'];
                $position = explode(" ", $fields['position']);
                $address->city_id = $city->id;
                $address->lat = $position[1];
                $address->long = $position[0];
                $address->save();
            }

            $this->city_id = $city->id;
            $this->address_id = $address->id;
            $this->status = 1;

            if ($this->save()) {
                $this->uploadGallery();
                return true;
            }
        }

        return false;
    }

    /**
      * Обработчик сохранения галереи для задания
    */
    public function uploadGallery()
    {
        if ($this->filesUpload = UploadedFile::getInstances($this, 'filesUpload')) {
            foreach ($this->filesUpload as $file) {
                $galleryFile = new Gallery();
                $galleryFile->post_id = $this->id;
                $galleryFile->post_type = 'task';
                $galleryFile->link = '/uploads/' . $file->baseName . '.' . $file->extension;
                $galleryFile->user_id = \Yii::$app->user->identity->id;
                $galleryFile->created_at = strtotime('now');
                $galleryFile->save();
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
        }
    }

    /**
      * Обработчик обновления статуса задания
      * @param int $status
    */
    public function updateStatus($status)
    {
        $this->status = $status; // В работе
        $this->save();
    }

    /**
      * Обработчик фильтрации заданий со страницы "Мои задания"
      * @param array $filter
    */
    public function filterMyTasks($filter)
    {
        $query = Task::find()->join('LEFT JOIN', 'responds', 'tasks.id = responds.task_id');

        if ($filter) {
            switch($filter['status']) {
                case 'finished':
                   $query->andWhere(['tasks.status' => '5']);
                   $query->andWhere(['responds.user_id' => \Yii::$app->user->identity->id]);
                   $query->orWhere(['tasks.user_created' => \Yii::$app->user->identity->id]);
                   $query->andWhere(['responds.is_accepted' => 1]);
                break;
                case 'new':   
                    $query->andWhere(['tasks.status' => '1']);
                    $query->andWhere(['tasks.user_created' => \Yii::$app->user->identity->id]);
                break;
                case 'current': 
                    $query->andWhere(['tasks.status' => '2']);
                    $query->andWhere(['responds.user_id' => \Yii::$app->user->identity->id]);
                    $query->orWhere(['tasks.user_created' => \Yii::$app->user->identity->id]);
                    $query->andWhere(['responds.is_accepted' => 1]);
                break;
                case 'canceled':
                    $query->andWhere(['tasks.status' => '3'])->orWhere(['tasks.status' => '6']);
                    $query->andWhere(['responds.user_id' => \Yii::$app->user->identity->id]);
                    $query->orWhere(['tasks.user_created' => \Yii::$app->user->identity->id]);
                    $query->andWhere(['responds.is_accepted' => 1]);
                break;
                case 'expired':
                    $query->andWhere(['<', 'tasks.expire_date', strtotime('now')]);
                    $query->andWhere(['responds.user_id' => \Yii::$app->user->identity->id]);
                    $query->andWhere(['responds.is_accepted' => 1]);
                break;
            }
        } else {
            $query->andWhere(['tasks.status' => '1']);
            $query->andWhere(['tasks.user_created' => \Yii::$app->user->identity->id]);
        }
        
        return $query;
    }

    /**
      * Обработчик фильтрации заданий со страницы "Задания"
      * @param array $filter
      * @param object $query
    */
    public function filterTasks($filter, $query)
    {
        if ($filter) {

            if (isset($filter['city'])) {
                $query->where(['tasks.city_id' => $filter['city']]);
            }

            if (isset($filter['category'])) {
                $query->andWhere(['in', 'category_id', $filter['category']]);
            } 

            if (isset($filter['work_type'])) {
                $query->andWhere(['in', 'work_type_id', $filter['work_type']]);
            }

            if (!empty($filter['period'])) {
                switch ($filter['period']) {
                    case 'day':
                            $query->andWhere('DATE(FROM_UNIXTIME(created_at)) = DATE(NOW())');
                        break;
                    case 'week':
                            $query->andWhere('WEEK(FROM_UNIXTIME(created_at)) = WEEK(NOW())');
                        break;
                    case 'month':
                            $query->andWhere('MONTH(FROM_UNIXTIME(created_at)) = MONTH(NOW())');
                        break;
                }
            }

            if (!empty($filter['sQuery'])) {
                $query->andWhere(['like', 'title', $filter['sQuery']]);
            }
        }

        return $query;
    }

}
