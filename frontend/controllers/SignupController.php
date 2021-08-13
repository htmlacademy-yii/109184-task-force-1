<?php
namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use frontend\models\SignupForm as SignupForm;
use frontend\models\User as User;

/**
 * Signup controller
 */
class SignupController extends Controller
{
    public function actionIndex()
    {
    	$model = new SignupForm();
        
    	if ($model->load(\Yii::$app->request->post())) {
	        if ($model->signup()) {
                $identity = User::findOne(['email' => $model->email]);
                if (Yii::$app->user->login($identity)) {
                    return $this->goHome();
                }
	        }
	    }
        return $this->render('index', compact('model'));
    } 

    public function actionChoose()
    {
        return $this->render('choose-role');
    }
}
