<?php

/*
echo json_encode(array(
        'space'=>'RevSpace',
        'url'=>'https://revspace.nl/',
        'open'=>TRUE,
        'address' => 'Binckhorstlaan 172, 2595 BT Den Haag, The Netherlands',
        'cam' => 'http://cam1.revspace.nl/',
        'checkins' => array('gmc'=>array('time' => time()), 'drdude' => array('time'=>time())),
));
*/

require_once('Database.class.php');
require_once('IButton.class.php');

$db=new Database('res/events.db');

$status=$db->spaceStatus();

$log=$db->unlockLog();

$checkins=array();
foreach($log as $event) {
  $checkins[] = array(
	'name' => $event['value']->getStatus(),
	'type' => 'check-in',
	't' => strtotime($event['t'])
  );
}

$reply=array(
	'api'		=> '0.9',
	'space'		=> 'RevSpace',
	'url'		=> 'https://revspace.nl',
	'address' 	=> 'Binckhorstlaan 172, 2595 BT Den Haag, The Netherlands',
	'contact'	=> array(
	  'phone'		=> '+31702127681',
	  'ml'			=> 'general@revspace.nl',
	  'irc'			=> 'irc://freenode/#revspace'
	),
	'cam'		=> array('http://cam1.revspace.nl/'),
	'logo'		=> 'https://revspace.nl/mediawiki/RevspaceLogoOnGreen.png',
	'open'		=> ($status['value']=='open'),
	'lastchange'	=> strtotime($status['t']),
	'checkins'	=> $checkins
);

echo json_encode($reply);

?>
