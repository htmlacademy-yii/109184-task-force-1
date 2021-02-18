<?php
namespace TaskForce;

class Refuse extends AbstractAction 
{
	public function getName() : string
	{
		return "Отказаться от задания";
	}

	public function getSystemName() : string
	{
		return "refuse";
	}

	public function checkRole(int $executantID, int $clientID, int $currentUserID) : bool
	{
		if ($currentUserID == $executantID) {
			return true;
		}

		return false;
	}
}