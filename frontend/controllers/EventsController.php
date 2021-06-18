<?php
namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use frontend\models\User as User;
use frontend\models\Gallery as Gallery;
use frontend\models\Address as Address;
use frontend\models\City as City;
use frontend\models\Category as Category;
use frontend\models\AccountForm as AccountForm;
use yii\web\UploadedFile;

/**
 * Events controller
 */
class EventsController extends SecuredController
{
    public function actionIndex()
    {
    }
}