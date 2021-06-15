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
 * Account controller
 */
class AccountController extends SecuredController
{
    public function actionIndex()
    {
        $model = User::find()->select(['id', 'email', 'name', 'birthdate', 'city_id', 'about', 'phone', 'telegram', 'skype', 'avatar', 'specifications'])->where([
             'id' => \Yii::$app->user->identity->id,
        ])->one();
        // $model = User::findOne(\Yii::$app->user->identity->id);

        $categories = Category::find()->all();
         
        if ($model->load(\Yii::$app->request->post())) {
            $fields = \Yii::$app->request->post();
         
            
            if ($model->validate()) {
                if (empty($fields['categories'])) {
                    $model->role_id = 4;
                } else {
                    $model->role_id = 3;
                }

                $model->email = $fields['User']['email'];
                $model->birthdate = strtotime($fields['User']['birthdate']);
                $model->city_id = $fields['User']['city_id'];
                $model->about = $fields['User']['about'];
                $model->phone = $fields['User']['phone'];
                $model->telegram = $fields['User']['telegram'];
                $model->skype = $fields['User']['skype'];
                $model->specifications = implode(',', $fields['categories']);
                $model->show_contacts = $fields['show_contacts'] ?? 0;
                $model->show_profile = $fields['show_profile'] ?? 0;
                $model->new_message = $fields['new_message'] ?? 0;
                $model->task_actions = $fields['task_actions'] ?? 0;
                $model->new_review = $fields['new_review'] ?? 0;
                $model->updated_at = strtotime('now');

                if ($model->save()) {
                    $model->avatarUpload = UploadedFile::getInstances($model, 'avatarUpload');

                    if ($model->avatarUpload) {
                        foreach ($model->avatarUpload as $file) {
                            $model->avatar = '/uploads/' . $file->baseName . '.' . $file->extension;
                            $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                        }
                    }

                    $model->portfolioUpload = UploadedFile::getInstances($model, 'portfolioUpload');

                    if ($model->portfolioUpload) {
                        foreach ($model->portfolioUpload as $file) {
                            $galleryFile = new Gallery();
                            $galleryFile->post_type = 'portfolio';
                            $galleryFile->link = '/uploads/' . $file->baseName . '.' . $file->extension;
                            $galleryFile->user_id = \Yii::$app->user->identity->id;
                            $galleryFile->created_at = strtotime('now');
                            $galleryFile->save();
                            $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                        }
                    }
                }
            }
        }

        return $this->render('index', compact('model', 'categories'));
    }
}