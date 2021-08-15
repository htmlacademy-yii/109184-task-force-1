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
    	$query = Task::find();

    	$tasks = $query
    	->where(['tasks.status' => '1'])
    	->with('category')
    	->with('city');

		$model = new TaskFilterForm();
		$model->getCategory();
		$model->getWorkType();
		$model->getPeriod();

        $filter = Yii::$app->request->get() ? Yii::$app->request->get() : Yii::$app->request->post();
        
        if (\Yii::$app->user->identity->city_id != 0) {
            $query->where(['tasks.city_id' => \Yii::$app->user->identity->city_id]);
        }
        
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

        return $this->render('task', compact('tasks', 'responds', 'responseForm', 'requestForm', 'refuseForm'));
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
            $fields = \Yii::$app->request->post();

            if ($model->createTask($fields)) {
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
        $taskID = '';

        if (\Yii::$app->user->identity->role_id == 4) {
            if ($id) {
                $respond = Respond::findOne($id);
                $respond->updateStatus(0);

                $task = Task::findOne($respond->task_id);
            }
        } else if (\Yii::$app->user->identity->role_id == 3) {
            if (\Yii::$app->request->post()) {
                $fields = \Yii::$app->request->post();
                $taskID = $fields['task_id'];

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
            $fields = \Yii::$app->request->post();

            $respond = new Respond();
            if ($respond->createRespond($fields)) {
                (new Notification())->setNotification(['type' => 1, 'task_id' => $fields['task_id'], 'user_id' => \Yii::$app->user->identity->id ]);
            }
        }
        
        return $this->redirect(['task/view/' . $respond->task_id]);
    }

    /**
      * Обработчик завершения задания заказчиком + создание отзыва исполнителю
    */
    public function actionRequest() 
    {
        if (\Yii::$app->request->post()) {
            $fields = \Yii::$app->request->post();
            $task_id = $fields['task_id'];

            $task = Task::findOne($task_id);
            $task->updateStatus(5); // Завершено

            $review = new Review();
            $review->createReview($fields);

            (new Notification())->setNotification(['type' => 5, 'task_id' => $task->id, 'user_id' => \Yii::$app->user->identity->id ]);
        }

        return $this->redirect(['task/view/' . $task->id]);
    }
}
