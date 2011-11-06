<?php

abstract class EventListener {
	protected $eventlist;

	public function __construct() {
	  EventManager::register($this,$this->eventlist);
	}

	abstract function event($name,$value);
}
