<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class RefuseForm extends Model
{
    public $task_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['task_id', 'number']
        ];
    }
}
