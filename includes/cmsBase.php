<?php
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
}
?>