<?php

class IrcListener extends  EventListener {
	protected $eventlist=array('door','unlock','spaceloop','status');

	public function event($name,$value) {
	  switch($name) {
	    case 'door':   
	      if($value=='open') $msg="opened"; else $msg="closed";
	      break;
	    case 'unlock': 
	      $msg="is unlocked by ".$value->getAnonymized();
	      break;
	    case 'spaceloop':
	      if($value=='open') 
			$msg="is happy to report that the spaceloop is open"; 
	      else
			$msg="is happy to report that the spaceloop is closed";
	      break;
	    case 'status':
	      if($value=='open') 
			$msg="happily announces that the space just opened";
	      else
			$msg="sadly brings news that the space is closed";
	      break;
	    default:
	      $msg="is confused";
	      break;
	  }

	  $f=fopen("/home/shellbot/shellbot.input","a")
  	    or die("Unable to open irc socket\n");
	  fputs($f,"PRIVMSG #revspace :".chr(1)."ACTION ".$msg.chr(1)."\n");
	  fclose($f);
	}
}

?>
