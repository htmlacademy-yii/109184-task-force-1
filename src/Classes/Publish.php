<?php
namespace TaskForce;

class Publish extends AbstractAction 
{
	public function getName() : string
	{
		return "Опубликовать задание";
	}

	public function getSystemName() : string
	{
		return "publish";
	}

	public function checkRole(int $executantID, int $clientID, int $currentUserID) : bool
	{
		if ($currentUserID == $clientID) {
			return true;
		}

		return false;
	}
}