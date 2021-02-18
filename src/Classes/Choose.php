<?php
namespace TaskForce;

class Choose extends AbstractAction 
{
	public function getName() : string
	{
		return "Выбрать исполнителя";
	}

	public function getSystemName() : string
	{
		return "choose";
	}

	public function checkRole(int $executantID, int $clientID, int $currentUserID) : bool
	{
		if ($currentUserID == $clientID) {
			return true;
		}

		return false;
	}
}