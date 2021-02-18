<?php
namespace TaskForce;

class Cancel extends AbstractAction 
{
	public function getName() : string
	{
		return "Отменить задание";
	}

	public function getSystemName() : string
	{
		return "cancel";
	}

	public function checkRole(int $executantID, int $clientID, int $currentUserID) : bool
	{
		if ($currentUserID == $clientID) {
			return true;
		}

		return false;
	}
}