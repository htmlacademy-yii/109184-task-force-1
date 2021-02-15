<?php
namespace TaskForce;

class Publish extends AbstractAction 
{
	public function getName() 
	{
		return "Опубликовать задание";
	}

	public function getSystemName() 
	{
		return "publish";
	}

	public function checkRole($executantID, $clientID, $currentUserID) 
	{
		if ($currentUserID == $clientID) {
			return true;
		}

		return false;
	}
}