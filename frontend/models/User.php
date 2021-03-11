<?php

namespace frontend\models;

use Yii;

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
 * @property Cities $city
 * @property UserRoles $role
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'birthdate', 'city_id', 'show_profile', 'show_contacts', 'created_at', 'updated_at'], 'integer'],
            [['about'], 'required'],
            [['about', 'specifications', 'portfolio'], 'string'],
            [['balance', 'rating'], 'number'],
            [['login', 'password', 'email', 'name', 'phone', 'skype', 'telegram', 'avatar'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::className(), 'targetAttribute' => ['city_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserRoles::className(), 'targetAttribute' => ['role_id' => 'id']],
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
        return $this->hasMany(Favourites::className(), ['user_added' => 'id']);
    }

    /**
     * Gets query for [[Favourites0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavourites0()
    {
        return $this->hasMany(Favourites::className(), ['user_favourite' => 'id']);
    }

    /**
     * Gets query for [[Galleries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGalleries()
    {
        return $this->hasMany(Gallery::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::className(), ['user_from' => 'id']);
    }

    /**
     * Gets query for [[Messages0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages0()
    {
        return $this->hasMany(Messages::className(), ['user_to' => 'id']);
    }

    /**
     * Gets query for [[Responds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(Responds::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::className(), ['user_created' => 'id']);
    }

    /**
     * Gets query for [[Reviews0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews0()
    {
        return $this->hasMany(Reviews::className(), ['user_reciever' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::className(), ['user_created' => 'id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::className(), ['id' => 'city_id']);
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
}
