<?php
if(!isset($security_check))
{
	echo "This is restricted directory";
	exit();
}

require_once('libraries/core/database/databaseMySql.php');
class CmsBase{
	//Anything here will be accessible to all of the CMS. 
	//Make sure everything here should be static and be using singlton design pattern
	public function getDbo()
	{
		static $dbobject = null;
        if (null === $dbobject) {
          $dbobject = new DatabaseMySql();            
        }
       return $dbobject;
	}
	protected function is_login()
	{
		session_start();
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
		return false;
	}
}
?>