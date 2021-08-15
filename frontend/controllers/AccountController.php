<?php
namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use frontend\models\User as User;
use frontend\models\Gallery as Gallery;
use frontend\models\City as City;
use frontend\models\Category as Category;
use frontend\models\CategoryUsers as CategoryUsers;
use yii\web\UploadedFile;

/**
 * Account controller
 */
class AccountController extends SecuredController
{
    /**
      * Обработчик формы редактирования аккаунта
    */
    public function actionIndex()
    {
        UploadedFile::reset();
        
        $model = User::find()->select(['id', 'email', 'name', 'birthdate', 'city_id', 'about', 'phone', 'telegram', 'skype', 'avatar', 'specifications'])->where([
             'id' => \Yii::$app->user->identity->id,
        ])->one();

        $categories = Category::find()->all();
        
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $fields = \Yii::$app->request->post();

            if (empty($fields['categories'])) {
                $model->role_id = 4;
            } else {
                $model->role_id = 3;
                foreach($fields['categories'] as $category) {
                    $catUser = new CategoryUsers();
                    $catUser->addCategory($category);
                }
            }

            $model->updateAccount($fields);
        }

        return $this->render('index', compact('model', 'categories'));
    }

    /**
      * Обработчик удаления форографии из портфолио
    */
    public function actionDelete()
    {
        if ($data = \Yii::$app->request->get()) {
            if (Gallery::findOne($data['id'])->delete()) return true;
        }

        return false;
    }
}