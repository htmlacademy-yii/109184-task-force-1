<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class RequestForm extends Model
{
    public $completion;
    public $comment;
    public $task_id;
    public $rating;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['comment', 'string']
        ];
    }
}
