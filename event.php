<?php

/**
 * unlocked - key hash
 *  - irc
 *  - log
 *  - spacestate json api
 * door
 *  - irc
 * status
 *  - irc
 *  - twitter
 *  - spacestate json api
 * spaceloop
 *  - irc
 *  - relais
 */

require_once('Database.class.php');
require_once('EventManager.class.php');
require_once('IButton.class.php');

EventManager::loadPlugins();

if(isset($_GET['event']) && isset($_GET['value'])) {
  switch($_GET['event']) {
    case 'door':
	if($_GET['value']=='open') {
	  EventManager::dispatch('door','open');
	} else {
	  EventManager::dispatch('door','closed');
	}
	break;
    case 'status':
	if($_GET['value']=='open') {
	  EventManager::dispatch('status','open');
	} else {
	  EventManager::dispatch('status','closed');
	}
	break;
    case 'unlock':
	if(preg_match('|^([0-9a-zA-Z]+)$|',$_GET['value'],$matches)) {
	  $who=new IButton($matches[1]);
	  EventManager::dispatch('unlock',$who);
	}
        break;
    case 'spaceloop':
	if($_GET['value']=='open') {
	  EventManager::dispatch('spaceloop','open');
	} else {
	  EventManager::dispatch('spaceloop','closed');
	}
        break;
  }
}

?>
