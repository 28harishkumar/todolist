<?php
require_once('cmsBase.php');
class CmsApplication extends CmsBase{
	//here we can write as many functions as we want and those functions will be called by user directly.
	function run()
	{
		$method=(isset($_REQUEST['task']))?$_REQUEST['task']:'display';
		$this->$method();
	}
	function display()
	{
		echo 'this is base display';
	}
	protected function redirect($url=null,$msg=null)
	{
		if(empty($url))
		{
			header('Location:index.php');exit(0);
		}
		else
		{
			header('Location:'.$url);exit(0);
		}
	}
}
?>