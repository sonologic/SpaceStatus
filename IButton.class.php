<?php

include_once('config.php');

if(!isset($ldapconn)) {
}

class IButton {
  private $anonymized="anoniempje";
  private $actual="unknown";

  public function __construct($givenHash) {
    global $ldap_uri;
    global $ldap_binddn;
    global $ldap_bindpw;
    global $ldap_basedn;
    global $secret;

    $ldapconn = ldap_connect($ldap_uri)
      or die("Unable to connect to ldap server: ".ldap_error());

    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

    if(ldap_bind($ldapconn,$ldap_binddn,$ldap_bindpw)) {
      $result=ldap_search($ldapconn,$ldap_basedn,"(uid=*)",array("iButtonSerial","tweetEntry","chanmsgEntry","cn"))
        or die("ldap search failed");

      $entries=ldap_get_entries($ldapconn,$result)
        or die("ldap get entries failed");

      $who="anoniempje";
      $realwho="unknown";
      foreach($entries as $entry) {
        if(isset($entry[ibuttonserial][0])) {

          $key="";
          for($i=0;$i<8;$i++) {
            $key.=chr( ("0x".substr($entry[ibuttonserial][0],$i*2,2)) + 0);
          }

          $hash=hash("sha256",$secret.$key,false);

          if($hash==$givenHash) {
            if(isset($entry[chanmsgentry][0]) && $entry[chanmsgentry][0]=="TRUE") $this->anonymized=$entry[cn][0];
            $this->actual=$entry[cn][0];
	    return;
	  }
        }
      }
    }
  }

  public function getAnonymized() {
    return $this->anonymized;
  }

  public function getActual() {
    return $this->actual;
  }
}

?>
