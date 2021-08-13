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
    public function actionIndex()
    {
        $query = Task::find()->join('LEFT JOIN', 'responds', 'tasks.id = responds.task_id');

        $filter = Yii::$app->request->get();
        if ($filter) {
            switch($filter['status']) {
                case 'finished':
                   $query->andWhere(['tasks.status' => '5']);
                   $query->andWhere(['responds.user_id' => \Yii::$app->user->identity->id]);
                   $query->orWhere(['tasks.user_created' => \Yii::$app->user->identity->id]);
                   $query->andWhere(['responds.is_accepted' => 1]);
                break;
                case 'new':   
                    $query->andWhere(['tasks.status' => '1']);
                    $query->andWhere(['tasks.user_created' => \Yii::$app->user->identity->id]);
                break;
                case 'current': 
                    $query->andWhere(['tasks.status' => '2']);
                    $query->andWhere(['responds.user_id' => \Yii::$app->user->identity->id]);
                    $query->orWhere(['tasks.user_created' => \Yii::$app->user->identity->id]);
                    $query->andWhere(['responds.is_accepted' => 1]);
                break;
                case 'canceled':
                    $query->andWhere(['tasks.status' => '3'])->orWhere(['tasks.status' => '6']);
                    $query->andWhere(['responds.user_id' => \Yii::$app->user->identity->id]);
                    $query->orWhere(['tasks.user_created' => \Yii::$app->user->identity->id]);
                    $query->andWhere(['responds.is_accepted' => 1]);
                break;
                case 'expired':
                    $query->andWhere(['<', 'tasks.expire_date', strtotime('now')]);
                    $query->andWhere(['responds.user_id' => \Yii::$app->user->identity->id]);
                    $query->andWhere(['responds.is_accepted' => 1]);
                break;
            }
        } else {
            $query->andWhere(['tasks.status' => '1']);
            $query->andWhere(['tasks.user_created' => \Yii::$app->user->identity->id]);
        }
        
        $tasks = $query->all(); 
        return $this->render('index', ['tasks' => $tasks, 'filter' => $filter]);
    }
}