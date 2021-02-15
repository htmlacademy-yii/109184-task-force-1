<?php
namespace TaskForce;

class Refuse extends AbstractAction 
{
	public function getName() 
	{
		return "Отказаться от задания";
	}

	public function getSystemName() 
	{
		return "refuse";
	}

	public function checkRole($executantID, $clientID, $currentUserID) 
	{
		if ($currentUserID == $executantID) {
			return true;
		}

		return false;
	}
}