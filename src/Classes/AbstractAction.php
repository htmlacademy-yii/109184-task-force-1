<?php
namespace TaskForce;

abstract class AbstractAction
{
	abstract public function getName();
	abstract public function getSystemName();
	abstract public function checkRole($executantID, $clientID, $currentUserID);
}