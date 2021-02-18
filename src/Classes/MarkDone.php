<?php
namespace TaskForce;

class MarkDone extends AbstractAction 
{
	public function getName() 
	{
		return "Отметить задание как выполненное";
	}

	public function getSystemName() 
	{
		return "mark_done";
	}

	public function checkRole($executantID, $clientID, $currentUserID) 
	{
		if ($currentUserID == $clientID) {
			return true;
		}

		return false;
	}
}