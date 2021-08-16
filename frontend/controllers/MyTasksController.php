<?php
namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use frontend\models\Task as Task;
use frontend\models\Respond as Respond;

/**
 * My Tasks controller
 */
class MytasksController extends SecuredController
{
    /**
      * Обработчик получения заданий пользователя
    */
    public function actionIndex()
    {
        $tasks = (new Task)->filterMyTasks(Yii::$app->request->get())->all();
        
        return $this->render('index', ['tasks' => $tasks, 'filter' => $filter]);
    }
}