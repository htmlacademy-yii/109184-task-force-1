<?php
namespace TaskForce;

class Respond extends AbstractAction 
{
	public function getName() : string
	{
		return "Откликнуться на задание";
	}

	public function getSystemName() : string
	{
		return "respond";
	}

	public function checkRole(int $executantID, int $clientID, int $currentUserID) : bool
	{
		if ($currentUserID == $executantID) {
			return true;
		}

		return false;
	}
}