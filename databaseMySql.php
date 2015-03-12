 <?php
 if(!isset($security_check))
{
	echo "This is restricted file";
	exit();
}

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
		$info = 'mysql:host='.$this->dbserver.';dbname='.$this->dbname;
		$this->con = new PDO($info, $this->dbuser, $this->dbpass);//data server connection

		if (!$this->con)
		{
			die('Could not connect: ' . mysql_error());
		}
	 }
	 private function disconnect()
	 {
	    $this->con = null;
	 }
	 function quote($arg)
	 {
	 	$this->connect();
	 	$arg = $this->con->quote($arg);
	 	$this->disconnect();
	 	return $arg;
	 }
	 function query($sql)
	 {			
		$this->connect();
		$res=$this->con->query($sql);
		$this->disconnect();
		return $res;
	 }
	 function prepare($sql,$args)
	 {
	 	$this->connect();
		$stmt=$this->con->prepare($sql);
		$stmt->execute($args);
		$this->disconnect();
		return $stmt;
	 }
	 function loadResult($sql,$args)
	 {
		$this->connect();
		$sth = $this->con->prepare($sql);
		$sth->execute($args);
		$rows = $sth->fetchAll();
		$this->disconnect();
		return $rows;
	 }
	 function loadSingleResult($sql,$args)
	 {
		$this->connect();
		$sth = $this->con->prepare($sql);
		$sth->execute($args);
		$row = $sth->fetch();		
		$this->disconnect();
		return $row;
	 }
 }

 ?>