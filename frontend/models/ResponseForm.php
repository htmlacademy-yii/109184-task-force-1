<?php
namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class ResponseForm extends Model
{
    public $price;
    public $comment;
    public $task_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['price', 'number'],
            ['price', 'compare', 'compareValue' => 0, 'operator' => '>=', 'type' => 'number', 'message' => 'Поле "Бюджет" должно быть положительным числом'],
            ['comment', 'string'],
        ];
    }
}
