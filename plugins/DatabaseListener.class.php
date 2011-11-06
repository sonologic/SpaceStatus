<?php

class DatabaseListener extends EventListener {
        protected $eventlist=array('door','status','unlock','spaceloop');
	protected $db;

        public function __construct() {
	  parent::__construct();
	  $this->db=new Database('res/events.db');
        }

	public function event($name,$value) {
	  $this->db->storeEvent($name,$value);
	}
}

?>
