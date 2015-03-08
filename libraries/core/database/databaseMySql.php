 <?php 
 class DatabaseMySql
 {

	 var $dbname='mytodo';
	 var $dbuser='root';
	 var $dbpass='itsmine';
	 var $dbserver='localhost';
	 var $con;//this variable stores the connection to db

	 function set_config($server,$db,$user,$pass)
	 {
		$this->dbserver=$server;
		$this->dbname=$db;
		$this->dbuser=$user;
		$this->dbpass=$pass;
	 }
	 private function connect()
	 {
		 $this->con = mysql_connect($this->dbserver,$this->dbuser,$this->dbpass);//data server connection
		  mysql_select_db($this->dbname,$this->con);
		  if (!$this->con)
		  {
			die('Could not connect: ' . mysql_error());
		  }
	 }
	 private function disconnect()
	 {
	    mysql_close($this->con);
	 }
	 function query($sql)
	 {			
		$this->connect();
		$res=mysql_query($sql);
		$this->disconnect();
		return $res;
	 }	
	 function loadResult($sql)
	 {
		$this->connect();
		$sth = mysql_query($sql);
		$rows = array();
		while($r = mysql_fetch_object($sth)) {
		$rows[] = $r;
		}
		$this->disconnect();
		return $rows;
	 }
	 function loadSingleResult($sql)
	 {
		$this->connect();
		$sth = mysql_query($sql);
		$row = mysql_fetch_object($sth);		
		$this->disconnect();
		return $row;
	 }	  

 } 
 ?>