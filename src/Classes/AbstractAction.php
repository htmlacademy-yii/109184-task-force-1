<?php
namespace TaskForce;

abstract class AbstractAction
{
	abstract public function getName() : string;
	abstract public function getSystemName() : string;
	abstract public function checkRole(int $executantID, int $clientID, int $currentUserID) : bool;
}