<?php
require_once('cmsBase.php');
class CmsWidget extends CmsBase{
	var $widgetPath='';
	var $widgetName='';
	var $parameters=array();
	function setWidget($position,$widgetName,$params=array())
	{
		$widget=new StdClass;
		$widget->name=$widgetName;
		$widget->parameters=$params;
		//if there is no widget in position then create a new array
		if(empty($this->widgetPositions[$position])) 
		{
			$this->widgetPositions[$position]=array($widget);
		}
		//if there is already a widget present in that position then just push new widget in array
		else
		{
			array_push($this->widgetPositions[$position],$widget);
		}		
	}
	function setWidgetPath($widgetName)
	{
		$this->widgetPath='widgets/'.$widgetName.'/';
		$this->widgetName=$widgetName;
	}
	function getWidgetPath()
	{
		return $this->widgetPath;
	}
	function display()
	{
		echo 'this will be default output of widget if this function is not overrided by derived class';
	}
	function run($widgetName,$params)// this function will be called by template function class to display widget
	{
		$this->parameters=$params;
		$this->setWidgetPath($widgetName);
		$this->display();
	}
}
?>