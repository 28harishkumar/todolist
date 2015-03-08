<?php
require_once('includes/cmsWidget.php');
class SidemenuWidget extends CmsWidget{		
	function display()
	{
		?>
		  <div>
		    <h3>Links</h3>
		  	<div class="col-lg-12" style="padding:0px;">
		      <ul class="nav nav-pills nav-stacked">
		        <li><a href="index.php?task=addtaskform">Add Todo Task</a></li>
		        <li><a href="index.php?task=viewtodolist">View Todo List</a></li>
		      </ul>
		    </div>
		    <h3>Labels</h3>
		  	<div class="col-lg-12" style="padding:0px;">
		      <ul class="nav nav-pills nav-stacked">
		      	<li><a href="index.php?task=viewtodolist">All</a></li>
		        <li><a href="index.php?task=viewtodolist&label=inbox">Inbox</a></li>
		        <li><a href="index.php?task=viewtodolist&label=stared">Stared</a></li>
		        <li><a href="index.php?task=viewtodolist&label=important">Important</a></li>
		      </ul>
		    </div>
		   </div>
		<?php
	}
}
?>