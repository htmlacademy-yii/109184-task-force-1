<?php
namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use frontend\models\Notification as Notification;

/**
 * Events controller
 */
class EventsController extends SecuredController
{
    /**
      * Обработчик получения событий
    */
    public function actionIndex()
    {
        $notifications = Notification::find()->select('notifications.*, tasks.title')
        ->with('notificationType')
        ->leftJoin('tasks', 'tasks.id = notifications.task_id')
        ->where(['user_created' => \Yii::$app->user->identity->id])
        ->andWhere(['<>', 'user_id',  \Yii::$app->user->identity->id])
        ->asArray()->all();
        
        return json_encode($notifications, JSON_PRETTY_PRINT);
    }
}