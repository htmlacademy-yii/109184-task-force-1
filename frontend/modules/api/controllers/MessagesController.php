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
    
    public function actionNew()
    {
      $requestParams = \Yii::$app->getRequest()->getBodyParams();
      
      if (empty($requestParams)) {
          $requestParams = \Yii::$app->getRequest()->getQueryParams();
      }

      $result = $this->modelClass::find()->where(['task_id' => $requestParams['task_id']])->all();
            
      return $result;
    } 
}