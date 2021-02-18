<?php
namespace TaskForce;

class MarkDone extends AbstractAction 
{
	public function getName() : string
	{
		return "Отметить задание как выполненное";
	}

	public function getSystemName() : string
	{
		return "mark_done";
	}

	public function checkRole(int $executantID, int $clientID, int $currentUserID) : bool
	{
		if ($currentUserID == $clientID) {
			return true;
		}

		return false;
	}
}