<?php
namespace TaskForce;

class Cancel extends AbstractAction 
{
	public function getName() {
		return "Отменить задание";
	}

	public function getSystemName() {
		return "cancel";
	}

	public function checkRole($executantID, $clientID, $currentUserID) {
		if ($currentUserID == $clientID) {
			return true;
		}

		return false;
	}
}