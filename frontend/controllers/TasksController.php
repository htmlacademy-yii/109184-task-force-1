<?php
namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use frontend\models\Task as Task;
use frontend\models\Respond as Respond;
use frontend\models\TaskFilterForm as TaskFilterForm;
use frontend\models\ResponseForm as ResponseForm;
use frontend\models\RequestForm as RequestForm;
use frontend\models\RefuseForm as RefuseForm;
use frontend\models\Gallery as Gallery;
use frontend\models\Address as Address;
use frontend\models\City as City;
use frontend\models\Notification as Notification;
use yii\web\UploadedFile;
use yii\data\Pagination;
use yii\caching\TagDependency;

/**
 * Tasks controller
 */
class TasksController extends SecuredController
{
    /**
      * Обработчик вызова всех заданий со статусом NEW
    */
    public function actionIndex()
    {
		$model = new TaskFilterForm();
		$model->getCategory();
		$model->getWorkType();
		$model->getPeriod();

    	$query = Task::find()->where(['tasks.status' => '1'])
                             ->with('category')
                             ->with('city');

        $filter = Yii::$app->request->get() ? Yii::$app->request->get() : Yii::$app->request->post();

        if (\Yii::$app->user->identity->city_id != 0) {
            $query->where(['tasks.city_id' => \Yii::$app->user->identity->city_id]);
        }

        $query = (new Task)->filterTasks($filter, $query);

        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => 5]);

        $tasks = $query->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('tasks', compact('tasks', 'model', 'filter', 'pages'));
    }

    /**
      * Обработчик просмотра задания
      * @param int $id
    */
    public function actionView($id)
    {
    	$task = Task::findOne($id);

    	$responds = Respond::find()->with('user')->where(['task_id' => $id])->all();

        $responseForm = new ResponseForm();
        $requestForm = new RequestForm();
        $refuseForm = new RefuseForm();

        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найден");
        }

        return $this->render('task', compact('task', 'responds', 'responseForm', 'requestForm', 'refuseForm'));
    }

    /**
      * Обработчик создания задания
    */
    public function actionCreate()
    {
        if (\Yii::$app->user->identity->role_id != 4) {
            throw new NotFoundHttpException("Вы не можете создавать задания");
        }

        $model = new Task();
        
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->createTask(\Yii::$app->request->post())) {
                return $this->goHome();
            }
        }
    
        $errors = $model->getErrors();
        
        return $this->render('create', compact('model', 'errors'));
    }

    /**
      * Обработчик отказа от задания (если исполнитель, то задание провалено; если заказчик, то заявка исполнителя отклонена)
      * @param int $id
    */
    public function actionRefuse($id = false) 
    {
        $taskID = null;

        if ($fields = \Yii::$app->request->post()) {
            if (isset($fields['task_id'])) $taskID = $fields['task_id'];
        }

        if (\Yii::$app->user->identity->role_id == 4) {
            if ($id) {
                $respond = Respond::findOne($id);
                $respond->updateStatus(0);

                $task = Task::findOne($respond->task_id);
            }
        } else if (\Yii::$app->user->identity->role_id == 3) {
            if ($taskID) {
                $task = Task::findOne($taskID);
                $task->updateStatus(6); // Провалено

                (new Notification())->setNotification(['type' => 3, 'task_id' => $taskID, 'user_id' => \Yii::$app->user->identity->id ]);
            }
        }

        return $this->redirect(['task/view/' . $task->id]);
    }

    /**
      * Обработчик принятия задания от заказчика
      * @param int $id
    */
    public function actionAccept($id) 
    {
        $respond = Respond::findOne($id);
        $respond->updateStatus(1);

        $task = Task::findOne($respond->task_id);
        $task->updateStatus(2); // в работе

        (new Notification())->setNotification(['type' => 4, 'task_id' => $task->id, 'user_id' => \Yii::$app->user->identity->id ]);
        (new Notification())->setNotification(['type' => 6, 'task_id' => $task->id, 'user_id' => \Yii::$app->user->identity->id ]);

        return $this->redirect(['task/view/' . $task->id]);
    }

    /**
      * Обработчик отклика на задание исполнителем
    */
    public function actionRespond() 
    {
        $respond = new Respond();

        if ($respond->validate() && \Yii::$app->request->post()) {
            $respond = new Respond();
            if ($respond->createRespond(\Yii::$app->request->post())) {
                (new Notification())->setNotification(['type' => 1, 'task_id' => $respond->task_id, 'user_id' => \Yii::$app->user->identity->id ]);
            }
        }
        
        return $this->redirect(['task/view/' . $respond->task_id]);
    }

    /**
      * Обработчик завершения задания заказчиком + создание отзыва исполнителю
    */
    public function actionRequest() 
    {
        if ($fields = \Yii::$app->request->post()) {
            if (isset($fields['task_id'])) {

                $task_id = $fields['task_id'];

                $task = Task::findOne($task_id);
                $task->updateStatus(5); // Завершено

                $review = new Review();
                $review->createReview($fields);

                (new Notification())->setNotification(['type' => 5, 'task_id' => $task->id, 'user_id' => \Yii::$app->user->identity->id ]);
            }
        }

        return $this->redirect(['task/view/' . $task->id]);
    }
}
