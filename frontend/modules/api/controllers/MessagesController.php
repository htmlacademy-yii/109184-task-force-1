<?php
namespace frontend\modules\api\controllers;

use frontend\models\Message;
use yii\rest\ActiveController;
use yii\web\Controller;

/**
  * Default controller for the `api` module
  */
class MessagesController extends ActiveController
{
    public $modelClass = Message::class;
}