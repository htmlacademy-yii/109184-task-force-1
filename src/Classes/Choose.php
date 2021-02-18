<?php
namespace TaskForce;

class Choose extends AbstractAction 
{
	public function getName() 
	{
		return "Выбрать исполнителя";
	}

	public function getSystemName() 
	{
		return "choose";
	}

	public function checkRole($executantID, $clientID, $currentUserID) 
	{
		if ($currentUserID == $clientID) {
			return true;
		}

		return false;
	}
}