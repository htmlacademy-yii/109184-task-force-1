<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;
/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property int|null $role_id
 * @property string|null $login
 * @property string|null $password
 * @property string|null $email
 * @property string|null $name
 * @property string $about
 * @property int|null $birthdate
 * @property int|null $city_id
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $telegram
 * @property string|null $avatar
 * @property string|null $specifications
 * @property string|null $portfolio
 * @property float|null $balance
 * @property float|null $rating
 * @property int|null $show_profile
 * @property int|null $show_contacts
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Favourites[] $favourites
 * @property Favourites[] $favourites0
 * @property Gallery[] $galleries
 * @property Messages[] $messages
 * @property Messages[] $messages0
 * @property Responds[] $responds
 * @property Reviews[] $reviews
 * @property Reviews[] $reviews0
 * @property Tasks[] $tasks
 * @property Citu $city
 * @property UserRole $role
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $avatarUpload, $portfolioUpload, $password_reset_token;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public $password_repeat;

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

     /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'show_profile', 'show_contacts', 'created_at', 'updated_at'], 'integer'],
            // [['about'], 'default', 'value' => ''],
            [['about', 'specifications', 'portfolio'], 'string'],
            [['balance', 'rating'], 'number'],
            [['login', 'password', 'email', 'name', 'phone', 'skype', 'telegram', 'avatar'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserRole::className(), 'targetAttribute' => ['role_id' => 'id']],
            [['last_online'], 'default', 'value' => 0],
            [['about'], 'default', 'value' => ''],
            [['source_id'], 'default', 'value' => ''],
            [['avatarUpload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['portfolioUpload'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 10, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role ID',
            'login' => 'Login',
            'password' => 'Password',
            'email' => 'Email',
            'name' => 'Name',
            'about' => 'About',
            'birthdate' => 'Birthdate',
            'city_id' => 'City ID',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'telegram' => 'Telegram',
            'avatar' => 'Avatar',
            'specifications' => 'Specifications',
            'portfolio' => 'Portfolio',
            'balance' => 'Balance',
            'rating' => 'Rating',
            'show_profile' => 'Show Profile',
            'show_contacts' => 'Show Contacts',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Favourites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavourites()
    {
        return $this->hasMany(Favourite::className(), ['user_added' => 'id']);
    }

    /**
     * Gets query for [[Favourites0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavouriteMe()
    {
        return $this->hasMany(Favourite::className(), ['user_favourite' => 'id']);
    }

    /**
     * Gets query for [[Galleries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasMany(Gallery::className(), ['user_id' => 'id'])->where(['post_type' => 'portfolio']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessagesTo()
    {
        return $this->hasMany(Message::className(), ['user_from' => 'id']);
    }

    /**
     * Gets query for [[Messages0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessagesFrom()
    {
        return $this->hasMany(Message::className(), ['user_to' => 'id']);
    }

    /**
     * Gets query for [[Responds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRespond()
    {
        return $this->hasMany(Respond::className(), ['user_id' => 'id'])
            ->join('LEFT JOIN', 'tasks', 'tasks.id = responds.task_id')
            ->andWhere(['tasks.status' => '5'])
            ->andWhere(['responds.user_id' => $this->id])
            ->andWhere(['responds.is_accepted' => 1]);;
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReview()
    {
        return $this->hasMany(Review::className(), ['user_created' => 'id']);
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviewsReciever()
    {
        return $this->hasMany(Review::className(), ['user_reciever' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasMany(Task::className(), ['user_created' => 'id'])
            ;
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
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(UserRoles::className(), ['id' => 'role_id']);
    }

    /**
     * Gets query for [[UserStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserStatus()
    {
        return $this->hasOne(getUserStatus::className(), ['id' => 'activity_status']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])->viaTable('category_users', ['user_id' => 'id']);;
    }

    /**
     * Gets query for [[CategoryUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryUsers()
    {
        return $this->hasOne(CategoryUsers::className(), ['user_id' => 'id']);
    }

    /**
      * Получение логина пользователя
    */
    public function getLogin()
    {
        return $this->login;
    }

    /**
      * Создание пользователя при регистрации через сервис Вконтакте
      * @param array $attributes
      * @param string $password
    */
    public function createUser($attributes, $password)
    {
        if (!empty($attributes)) {
            $user = new User([
                'login' => $attributes['nickname'] ?? "",
                'name' => ($attributes['first_name'] || $attributes['first_name']) ? $attributes['first_name'] . " " . $attributes['last_name'] : "",
                'email' => $attributes['email'] ?? null,
                'password' => $password ?? "",
                'birthdate' => ($attributes['bdate']) ? strtotime($attributes['bdate']) : 0,
                'avatar' => $attributes['photo'] ?? "",
                'created_at' => time(),
                'source_id' => $attributes['id'] ?? 0
            ]);
            $user->generateAuthKey();
            $user->generatePasswordResetToken();

            if ($user->save()) {
                return true;
            }
        }
        return false;
    }

    /**
      * Обновление данных аккаунта
      * @param array $fields
    */
    public function updateAccount($fields)
    {
        if (!empty($fields)) {
            $this->email = $fields['User']['email'] ?? "";
            $this->birthdate = ($fields['User']['birthdate']) ? strtotime($fields['User']['birthdate']) : 0;
            $this->city_id = $fields['User']['city_id'] ?? 0;
            $this->about = $fields['User']['about'] ?? "";
            $this->phone = $fields['User']['phone'] ?? "";
            $this->telegram = $fields['User']['telegram'] ?? "";
            $this->skype = $fields['User']['skype'] ?? "";
            $this->show_contacts = $fields['show_contacts'] ?? 0;
            $this->show_profile = $fields['show_profile'] ?? 0;
            $this->new_message = $fields['new_message'] ?? 0;
            $this->task_actions = $fields['task_actions'] ?? 0;
            $this->new_review = $fields['new_review'] ?? 0;
            $this->updated_at = strtotime('now');

            if ($this->save()) {
                $this->uploadAvatar();
                $this->uploadPortfolio();

                return true;
            }
        }

        return false;
    }

    /**
      * Обработчик загрузки аватара пользователя
    */
    public function uploadAvatar()
    {
        if ($this->avatarUpload = UploadedFile::getInstances($this, 'avatarUpload')) {
            foreach ($this->avatarUpload as $file) {
                $this->avatar = '/uploads/' . $file->baseName . '.' . $file->extension;
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
        }

        $this->avatarUpload = '';
    }

    /**
      * Обработчик загрузки портфолио пользователя
    */
    public function uploadPortfolio()
    {
        if ($this->portfolioUpload = UploadedFile::getInstances($this, 'portfolioUpload')) {
            foreach ($this->portfolioUpload as $file) {
                $galleryFile = new Gallery();
                $galleryFile->post_type = 'portfolio';
                $galleryFile->link = '/uploads/' . $file->baseName . '.' . $file->extension;
                $galleryFile->user_id = \Yii::$app->user->identity->id;
                $galleryFile->created_at = strtotime('now');
                $galleryFile->save();
                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
            }
        }
    }

    /**
      * Обработчик фильтрации заданий со страницы "Исполнители"
      * @param array $filter
      * @param object $query
    */
    public function filterUsers($filter, $query)
    {
        if ($filter) {

            if (isset($filter['category'])) {
                $query->leftJoin('category_users', 'category_users.user_id = users.id')->andWhere(['in', 'category_users.category_id', $filter['category']]);
            }

            if (isset($filter['free'])) {
                $query->andWhere(['activity_status' => 1]);
            }

            if (isset($filter['online'])) {
                // сделать когда будет авторизация
            }

            if (isset($filter['has_reviews'])) {
                if (!empty($reviewUsers = Review::find()->select('user_reciever')->asArray()->column())) {
                    $query->andWhere(['in', 'id', $reviewUsers]);
                }
            }
            
            if (isset($filter['favourite'])) {
                if (!empty($favouriteUsers = Favourite::find()->select('user_favourite')->asArray()->column())) {
                    $query->andWhere(['in', 'id', $favouriteUsers]);
                }
            }

            if (!empty($filter['sQuery'])) {
                $query->andWhere(['like', 'name', $filter['sQuery']]);
            }
        }

        return $query;
    }
}
