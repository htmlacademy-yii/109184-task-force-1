<?php
namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\db\Query;
use frontend\models\User as User;
use frontend\models\UserFilterForm as UserFilterForm;
use frontend\models\Favourite as Favourite;
use frontend\models\Review as Review;
use frontend\models\Gallery as Gallery;
use yii\data\Pagination;

/**
 * Users controller
 */
class UsersController extends SecuredController
{
	/**
      * Получение списка пользователей
    */
    public function actionIndex()
    {
    	$query = User::find();
    	$users = $query
    	->where(['role_id' => '3'])
    	->with('city')->with('review')->with('respond');

    	$taskForm = new UserFilterForm();
		$taskForm->getCategory();
		$taskForm->getWorkType();
		
        $filter = Yii::$app->request->get() ? Yii::$app->request->get() : Yii::$app->request->post();
    	if ($filter) {

        	if (isset($filter['category'])) {
				$query->leftJoin('category_users', 'category_users.user_id = users.id')->andWhere(['in', 'category_users.category_id', $filter['category']]);
        	}

        	if (isset($filter['free'])) {
				$query->andWhere(['activity_status' => 1]);
			}

        	if (isset($filter['online'])) {
				// сделать когда будет авторизация
			}

        	if (isset($filter['has_reviews'])) {
        		if (!empty($reviewUsers = Review::find()->select('user_reciever')->asArray()->column())) {
					$query->andWhere(['in', 'id', $reviewUsers]);
        		}
			}
			
        	if (isset($filter['favourite'])) {
				if (!empty($favouriteUsers = Favourite::find()->select('user_favourite')->asArray()->column())) {
					$query->andWhere(['in', 'id', $favouriteUsers]);
				}
			}

			if (!empty($filter['sQuery'])) {
				$query->andWhere(['like', 'name', $filter['sQuery']]);
			}
        }

        $pages = new Pagination(['totalCount' => $query->count()]);
        $users = $query->offset($pages->offset)
        ->limit($pages->limit)->all();

        return $this->render('users', ['users' => $users, 'model' => $taskForm, 'filter' => $filter, 'pages' => $pages]);
    }

	/**
      * Просмотр пользователя
      * @param int $id
    */
    public function actionView($id)
    {
        $user = User::find()->where(['users.id' => $id])
        ->with('city')->with('gallery')->with('category')->one();
      
        $reviews = Review::find()->with('task')->with('userReciever')->where(['user_reciever' => $id])->all();
        
        if (!$user) {
            throw new NotFoundHttpException("Пользователь с ID $id не найден");
        }

        return $this->render('user', ['user' => $user, 'reviews' => $reviews]);
    }

    /**
      * Добавление пользователя в избранное
    */
    public function actionBookmark() 
    {
    	if (Yii::$app->request->get()) {
    		if ($favUser = Favourite::find()->where(['user_added' => \Yii::$app->user->identity->id])->andWhere(['user_favourite' => Yii::$app->request->get()['id']])->one()) {
    			if ($favUser->delete()) {
	    			return true;
	    		}
    		} else {
    			$args = [
	    			'user_added' => \Yii::$app->user->identity->id,
	    			'user_favourite' => Yii::$app->request->get()['id'],
	    			'created_at' => time()
	    		];
	    		
	    		$favUser = new Favourite();
	    		
	    		if ($favUser->createFavourite($args)) {
	    			return true;
	    		}
    		}
    	}

    	return false;
    }
}
