<?php
require_once('includes/cmsApplication.php');
class DefaultApplication extends CmsApplication{
		function addtodotask()
		{
			$title=$_REQUEST['title'];
			$desc=$_REQUEST['desc'];
			$end_date=$_REQUEST['end_date'];
			$label=$_REQUEST['label'];
			$start_date = date("Y-m-d");
			$sql="INSERT INTO todolist VALUES(NULL,'$title','$desc','0','$start_date','$end_date','$label')";
			$db=$this->getDbo();			
			if($db->query($sql))
			{				
				$this->redirect('index.php?task=viewtodolist');				
			}
			else
			{
				//$this->redirect();	
				echo $sql;			
			}

		}
		function edittodotask()
		{			
			$id=$_REQUEST['id'];
			$title=$_REQUEST['title'];
			$desc=$_REQUEST['desc'];
			$end_date=$_REQUEST['end_date'];
			$label=$_REQUEST['label'];
			$progress = $_REQUEST['progress'];
			$sql="UPDATE todolist as tasks SET tasks.title='$title',tasks.desc='$desc', tasks.progress='$progress', tasks.end_date='$end_date', tasks.label='$label' WHERE id=$id";
			$db=$this->getDbo();				
			if($db->query($sql))
			{					
				$this->redirect('index.php?task=viewtodolist');				
			}
			else
			{				
				$this->redirect();
			}			
		}
		function time_left($end_date)
		{
			$today = strtotime("now");
			$due_date = strtotime($end_date);
			if($due_date>$today)
			{
				$hours = $due_date - $today;
				$hours = $hours/3600;
				$time_left = $hours/24;
				
				if($time_left < 1)
				{
					$time_left = 'Less than 1 day';
				}
				else
				{
					$time_left = round($time_left).' days';
				}
			}
			else
			{
				$time_left = 'Expired';
			}

			echo $time_left;
		}
        function viewtodolist()
		{
			$label = $_REQUEST['label'] ? $_REQUEST['label']:'all';
			$db=$this->getDbo();
			if($label == 'all')
			{
				$sql="SELECT * FROM todolist";
			}	
			else
			{
				$sql="SELECT * FROM todolist WHERE label='$label'";
			}
			$rows=$db->loadResult($sql);
			?>
			<div class="todolist-app">
				<h2>Todolist Application Dashboard</h2>
				<h3>My Todolist<?php if($label != 'all'){echo ' label:'.$label;}?></h3>
				<table class="table">
					<tr>
						<th class="col-sm-3">Task</th>
						<th>Description</th>
						<th class="col-sm-2">Progress</th>
						<th class="col-sm-1">Time Left</th>
						<?php if($label == 'all'){echo '<th>Label</th>';}?>						
						<th>Actions</th>
					</tr>
					<?php				
					foreach($rows as $row)
					{
					?>
						<tr>
							<td><?php echo $row->title;?></td>
							<td><?php echo $row->desc;?></td>
							<td><div class="progress">
							  <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
							  aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" 
							  style="width:<?php echo $row->progress;?>%">
							  </div>
							</div></td>
							<td><?php $this->time_left($row->end_date);?></td>
							<?php if($label == 'all'){ echo '<td>'.$row->label.'</td>';}?>
							<td>
							   <a href="index.php?task=edittaskform&taskid=<?php echo $row->id;?>">Edit</a>|
							   <a href="index.php?task=deletetask&taskid=<?php echo $row->id;?>">Delete</a>
							 </td>
						</tr>
					<?php
					}
					?>
				</table>
			</div>			
			<?php
		}
		function deletetask()
		{
			$id=$_REQUEST['taskid'];
			$db=$this->getDbo();
			$sql="DELETE FROM todolist WHERE id=$id";
			$db->query($sql);
			$this->redirect('index.php?task=viewtodolist');			
		}
		function display()
		{
			$this->viewtodolist();
		}
	
		function addtaskform()
		{
			?>
			   <div>
			   <h2>Todolist Application Dashboard</h2>
			    <h3>Add New Todolist</h3>
								
				<form role="form">
					<input type="hidden" name="task" value="addtodotask"/>

					<div class="form-group">
						<label for="title">Title</label>
						<input type="text" name="title" class="form-control" />
					</div>

					<div class="form-group">
						<label for="desc">Description</label>
						<textarea name="desc" class="form-control"></textarea>
					</div>

					<div class="form-group">
						<label for="title">End Date (YYYY-MM-DD)</label>
						<input type="text" name="end_date" class="form-control"  />
					</div>

					<div class="form-group">
						<label for="title">Label</label>
						<select type="text" name="label" class="form-control">
							<option value="inbox">Inbox</option>
							<option value="important">Important</option>
							<option value="starred">Stared</option>
						</select>
					</div>

					<button type="submit" class="btn btn-default">Add Task</button>					
				</form>
			   </div>			  
			<?php
		}
		function edittaskform()
		{
			$id=$_REQUEST['taskid'];
			$sql="SELECT * FROM todolist WHERE id=$id";
			$db=$this->getDbo();
			$row=$db->loadSingleResult($sql);		
			?>
			   <div class="todolist-app">
			   <h2>Todolist Application Dashboard</h2>
			   <h3>Edit Todolist</h3>
				<form role="form">
					<input type="hidden" name="task" value="edittodotask"/>

					<input type="hidden" name="id" value="<?php echo $row->id;?>"/>

					<div class="form-group">
						<label for="title">Title</label>
						<input type="text" name="title" value="<?php echo $row->title;?>" class="form-control" />
					</div>

					<div class="form-group">
						<label for="desc">Description</label>
						<textarea name="desc" class="form-control" ><?php echo $row->desc;?></textarea>
					</div>

					<div class="form-group">
						<label for="title">Progress (in %)</label>
						<input type="text" name="progress" value="<?php echo $row->progress;?>" class="form-control"  />
					</div>

					<div class="form-group">
						<label for="title">End Date (YYYY-MM-DD)</label>
						<input type="text" name="end_date" value="<?php echo $row->end_date;?>" class="form-control"  />
					</div>

					<div class="form-group">
						<label for="title">Label</label>
						<select type="text" name="label" value="<?php echo $row->label;?>" class="form-control">
							<option value="inbox">Inbox</option>
							<option value="important">Important</option>
							<option value="starred">Stared</option>
						</select>
					</div>

					<button type="submit" class="btn btn-default">Submit</button>					
				</form>
			   </div>			  
			<?php
		}
}
?>