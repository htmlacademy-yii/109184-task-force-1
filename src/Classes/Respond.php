<?php
namespace TaskForce;

class Respond extends AbstractAction 
{
	public function getName() 
	{
		return "Откликнуться на задание";
	}

	public function getSystemName() 
	{
		return "respond";
	}

	public function checkRole($executantID, $clientID, $currentUserID) 
	{
		if ($currentUserID == $executantID) {
			return true;
		}

		return false;
	}
}