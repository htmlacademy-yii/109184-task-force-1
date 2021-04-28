<?php
namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use frontend\models\Task as Task;
use frontend\models\Respond as Respond;
use frontend\models\TaskFilterForm as TaskFilterForm;

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
		
    	if (Yii::$app->request->getIsPost()) {
        	$filter = Yii::$app->request->post();

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
    	$task = Task::find()->where(['tasks.id' => $id])->with('category')
    	->with('city')->with('user')->one();

    	$responds = Respond::find()->with('user')->where(['task_id' => $id])->all();
    	
        if (!$task) {
            throw new NotFoundHttpException("Задание с ID $id не найден");
        }

        return $this->render('task', ['task' => $task, 'responds' => $responds]);
    }
}
