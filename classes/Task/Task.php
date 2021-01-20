<?php

class Task {
	const STATUS_NEW = 'new'; // Новое
	const STATUS_CANCELED = 'canceled'; // Отменено
	const STATUS_INWORK = 'inwork'; // В работе
	const STATUS_COMPLETE = 'complete'; // Выполнено
	const STATUS_FAILED = 'failed'; // Провалено

	public $ExecutantID = 0;
	public $ClientID = 0;
	public $status = [];
	protected $actions = [];

	protected $statuses = [
		'new' => 'Задание опубликовано, исполнитель ещё не найден',
		'canceled' => 'Заказчик отменил задание',
		'inwork' => 'Заказчик выбрал исполнителя для задания',
		'complete' => 'Заказчик отметил задание как выполненное',
		'failed' => 'Исполнитель отказался от выполнения задания',
	];

	function __construct ($ExecutantID, $ClientID, $status) {
		$this->ExecutantID = $ExecutantID;
		$this->ClientID = $ClientID;

		$this->GetAvailableActions($status);
	}

	// Заказчик
	public function AddTask () {
		$this->GetStatus('action_add');
		return $this->statuses[$this->status];
	}
	public function CancelTask () {
		$this->GetStatus('action_cancel');
		return $this->statuses[$this->status];
	}
	public function ChooseExecutant () {
		$this->GetStatus('action_choose');
		return $this->statuses[$this->status];
	}
	public function MarkAsDone () {
		$this->GetStatus('action_mark_done');
		return $this->statuses[$this->status];
	}

	// Исполнитель
	public function RefuseTask () {
		$this->GetStatus('action_refuse');
		return $this->statuses[$this->status];
	}

	private function GetAvailableActions ($s) {
		switch ($s) {
			case 'new': return
				$this->actions = ['cancel', 'choose_executant'];
				break;
			case 'inwork': return
				$this->actions = ['mark_as_done', 'refuse'];
				break;
			default:
				$this->actions = "No actions available";
				break;
		}
	}

	public function GetStatus ($action) {
		switch ($action) {
			case 'action_add': return $this->status = self::STATUS_NEW;
				break;
			case 'action_cancel': return $this->status = self::STATUS_CANCELED;
				break;
			case 'action_choose': return $this->status = self::STATUS_INWORK;
				break;
			case 'action_mark_done': return $this->status = self::STATUS_COMPLETE;
				break;
			case 'action_refuse': return $this->status = self::STATUS_FAILED;
				break;
		}
	}
}