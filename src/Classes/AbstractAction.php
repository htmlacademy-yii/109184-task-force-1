<?php
namespace TaskForce;

abstract class AbstractAction
{
	abstract protected function getName();
	abstract protected function getSystemName();
	abstract protected function checkRole($executantID, $clientID, $currentUserID);
}