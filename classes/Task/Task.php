<?php

class Task
{
    const STATUS_NEW = 'new'; // Новое
    const STATUS_CANCELED = 'canceled'; // Отменено
    const STATUS_INWORK = 'inwork'; // В работе
    const STATUS_COMPLETE = 'complete'; // Выполнено
    const STATUS_FAILED = 'failed'; // Провалено

    const ACTION_PUBLISH = 'publish';
    const ACTION_CANCEL = 'cancel';
    const ACTION_CHOOSE = 'choose';
    const ACTION_MARK_DONE = 'mark_done';
    const ACTION_REFUSE = 'failed';
    const ACTION_RESPOND = 'respond';

    private $executantID = 0;
    private $clientID = 0;
    private $status = [];
    private $actions = [];

    public $actionsList = [
        'publish' => 'Опубликовать задание',
        'cancel' => 'Отменить задание',
        'choose' => 'Выбрать исполнителя',
        'mark_done' => 'Отметить задание как выполненное',
        'refuse' => 'Отказаться от задания',
        'respond' => 'Откликнуться на задание',
    ];

    public $statusesList = [
        'new' => 'Задание опубликовано, исполнитель ещё не найден',
        'canceled' => 'Заказчик отменил задание',
        'inwork' => 'Заказчик выбрал исполнителя для задания',
        'complete' => 'Заказчик отметил задание как выполненное',
        'failed' => 'Исполнитель отказался от выполнения задания',
    ];

    function __construct($executantID, $clientID)
    {
        $this->executantID = $executantID;
        $this->clientID = $clientID;
    }

    public function getAvailableActionsByStatus($status, $role)
    {
        switch ($status) {
            case 'new':
                if ($role == 'client') {
                    return $this->actions = [self::ACTION_CANCEL, self::ACTION_CHOOSE]; // не уверена, что так можно делать
                } else if ('executant') {
                    return $this->actions = [self::ACTION_RESPOND];
                }
                break;
            case 'inwork':
                if ($role == 'customer') {
                    return $this->actions = [self::ACTION_MARK_DONE];
                } else if ('executant') {
                    return $this->actions = [self::ACTION_REFUSE];
                }
                break;
            default:
                $this->actions = [];
                break;
        }
    }

    public function getStatus($action)
    {
        switch ($action) {
            case 'add':
                return $this->status = self::STATUS_NEW;
                break;
            case 'cancel':
                return $this->status = self::STATUS_CANCELED;
                break;
            case 'choose':
                return $this->status = self::STATUS_INWORK;
                break;
            case 'mark_done':
                return $this->status = self::STATUS_COMPLETE;
                break;
            case 'refuse':
                return $this->status = self::STATUS_FAILED;
                break;
        }
    }
}
