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
use yii\web\UploadedFile;

/**
 * Tasks controller
 */
class TasksController extends SecuredController
{
    public function actionIndex()
    {
    	$query = Task::find();
    	$tasks = $query
    	->where(['tasks.status' => '1'])
    	->with('category')
    	->with('city');

		$taskForm = new TaskFilterForm();
		$taskForm->getCategory();
		$taskForm->getWorkType();
		$taskForm->getPeriod();

        $filter = Yii::$app->request->post();
    	if ($filter) {

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

        $tasks = $query->all();
        return $this->render('tasks', ['tasks' => $tasks, 'model' => $taskForm, 'filter' => $filter]);
    }

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

        return $this->render('task', ['task' => $task, 'responds' => $responds, 'responseForm' => $responseForm, 'requestForm' => $requestForm, 'refuseForm' => $refuseForm]);
    }

    public function actionCreate()
    {
        if (\Yii::$app->user->identity->role_id != 4) {
            throw new NotFoundHttpException("Вы не можете создавать задания");
        }

        $model = new Task();
        
        $model->load(\Yii::$app->request->post());
        $fields = \Yii::$app->request->post();
        
        if ($model->validate()) {
            $model->title = $fields['Task']['title'];
            $model->description = $fields['Task']['description'];
            $model->category_id = $fields['Task']['category_id'];
            $model->price = $fields['Task']['price'];
            $model->expire_date = strtotime($fields['Task']['expire_date']);
            $model->status = 1;
            $model->user_created = \Yii::$app->user->identity->id;
            $model->created_at = strtotime('now');

            $model->filesUpload = UploadedFile::getInstances($model, 'filesUpload');

            if ($model->save()) {
                if ($model->filesUpload) {
                    foreach ($model->filesUpload as $file) {
                        $galleryFile = new Gallery();
                        $galleryFile->post_id = $model->id;
                        $galleryFile->post_type = 'task';
                        $galleryFile->link = '/uploads/' . $file->baseName . '.' . $file->extension;
                        $galleryFile->user_id = \Yii::$app->user->identity->id;
                        $galleryFile->created_at = strtotime('now');
                        $galleryFile->save();
                        $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                    }
                }

                return $this->goHome();
            }
        } 
    
        $errors = $model->getErrors();
        
        return $this->render('create', compact('model', 'errors'));
    }

    public function actionRefuse($id = false) {
        $taskID = '';

        if (\Yii::$app->user->identity->role_id == 4) {
            if ($id) {
                $respond = Respond::findOne($id);

                $respond->is_accepted = 0;
                $respond->save();

                $task = Task::findOne($respond->task_id);
            }
        } else if (\Yii::$app->user->identity->role_id == 3) {
            if (\Yii::$app->request->post()) {
                $fields = \Yii::$app->request->post();
                $taskID = $fields['task_id'];

                $task = Task::findOne($taskID);
                $task->status = 6; // Провалено
                $task->save();
            }
        }

        return $this->redirect(['task/view/' . $task->id]);
    }

    public function actionAccept($id) {
        $respond = Respond::findOne($id);

        $respond->is_accepted = 1;
        $respond->save();

        $task = Task::findOne($respond->task_id);
        $task->status = 2; // В работе
        $task->save();

        return $this->redirect(['task/view/' . $task->id]);
    }

    public function actionRespond() {
        $respond = new Respond();

        if (\Yii::$app->request->post()) {
            $fields = \Yii::$app->request->post();
            if ($respond->validate()) {
                $respond->task_id = $fields['ResponseForm']['task_id'];
                $respond->user_id = \Yii::$app->user->identity->id;
                $respond->price = $fields['ResponseForm']['price'];
                $respond->comment = $fields['ResponseForm']['comment'];
                $respond->created_at = strtotime('now');
                $respond->is_accepted = null;
                $respond->save();
            }
        }
        
        return $this->redirect(['task/view/' . $respond->id]);
    }

    public function actionRequest() {
        if (\Yii::$app->request->post()) {
            $fields = \Yii::$app->request->post();
            $task_id = $fields['task_id'];

            $task = Task::findOne($task_id);
            $task->status = 5; // Завершено
            $task->save();

            $review = new Review();
            $review->task_id = $task->id;
            $review->user_created = \Yii::$app->user->identity->id;
            $review->text = $fields['RequestForm']['task_id'];

            $userExecutant = Respond::find()->where(['task_id' => $task->id])->where(['status' => 1])->one();
            $review->user_reciever = $userExecutant->user_id;
            $review->rate = $fields['rating'];
            $review->created_at = strtotime('now');
            $review->save();
        }

        return $this->redirect(['task/view/' . $task->id]);
    }
}
