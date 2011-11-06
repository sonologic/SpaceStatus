<?php

require_once('EventListener.class.php');

class EventManager {
  static $listeners=array(
	   'door' => array(),
	   'status' => array(),
	   'spaceloop' => array(),
	   'unlock' => array()
	 );

  static function loadPlugins() {
    $plugins=glob('plugins/*.class.php');
    foreach($plugins as $plugin) {
	require_once($plugin);
	$class=str_replace('.class.php','',basename($plugin));
	
	$pluginObject=eval("\$rv=new $class(); return \$rv;");
    }
  }

  static function register($listener,$events) {
    foreach($events as $event) {
      if(isset(EventManager::$listeners[$event])) {
	EventManager::$listeners[$event][]=$listener;
      }
    }
  }

  static function dispatch($event,$value) {
    foreach(EventManager::$listeners[$event] as $listener) {
	$listener->event($event,$value);
    }
  }

}
?>
