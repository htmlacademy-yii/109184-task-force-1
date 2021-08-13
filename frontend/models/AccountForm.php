<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

use frontend\models\Category as Category;
use frontend\models\WorkType as WorkType;

class AccountForm extends Model
{
    /**
     * {@inheritdoc}
     */
    public $password_repeat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['about'], 'default', 'value' => ''],
            [['about', 'specifications', 'portfolio'], 'string'],
            [['password', 'password_repeat', 'email', 'phone', 'skype', 'telegram', 'avatar'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'skipOnEmpty' => false, 'message'=>"Пароли не совпадают" ],
        ];
    }
    
}
