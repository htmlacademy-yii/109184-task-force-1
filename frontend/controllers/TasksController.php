<?php
namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\db\Query;
use frontend\models\Task as Task;

/**
 * Tasks controller
 */
class TasksController extends Controller
{
    public function actionIndex()
    {
    	$tasks = Task::find()
    	->where(['tasks.status' => '1'])
    	->with('category')
    	->with('city')->asArray()->all();
    	
        return $this->render('tasks', ['tasks' => $tasks]);
    }
}
