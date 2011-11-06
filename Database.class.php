<?php

class Database {
  public function __construct($filename) {
    $this->conn=sqlite_open($filename) or die('failed to open database');

    $errors=error_reporting();
    error_reporting($errors & ~$errors);
    sqlite_query($this->conn,"create table events (id integer primary key,t timestamp,name varchar(32),type varchar(8),value text)");
    error_reporting($errors);
  }

  public function storeEvent($name,$value) {
    switch(gettype($value)) {
      case 'object':
	$type='object';
	$store=serialize($value);
	break;
      case 'integer':
	$type='int';
	$store="$value";
	break;
      case 'double':
	$type='double';
	$store="$value";
	break;
      case 'boolean':
	$type='bool';
	$store=$value?'t':'f';
	break;
      default:
	$type='string';
	$store=$value;
	break;
    }
    sqlite_query($this->conn,"insert into events (t,name,type,value) values (datetime('now'),'".sqlite_escape_string($name)."','$type','".sqlite_escape_string($store)."')");
    return sqlite_last_insert_rowid($this->conn);
  }

  public function spaceStatus() {
    $rows=sqlite_array_query($this->conn,"select * from events where name='status' order by t desc limit 1");
    if(count($rows)) {
      return $rows[0];
    }
    return array('id'=>NULL,'t'=>NULL,'name'=>'status','type'=>'string','value'=>'unknown');
  }

  public function loopStatus() {
    $rows=sqlite_array_query($this->conn,"select * from events where name='status' order by t desc limit 1");
    if(count($rows)) {
      return $rows[0];
    }
    return array('id'=>NULL,'t'=>NULL,'name'=>'status','type'=>'string','value'=>'unknown');
  }

  public function unlockLog($num=10) {
    $rows=sqlite_array_query($this->conn,"select * from events where name='unlock' order by t desc limit ".($num+0));
    for($i=0;$i<count($rows);$i++) {
      if($rows[$i]['type']=='object') $rows[$i]['value']=unserialize($rows[$i]['value']);
    }
    return $rows;
  }
}


?>
