<html>
<head>
	<title>MyCMS from screech</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link type='text/css' rel='stylesheet' href='<?php echo $this->getCurrentTemplatePath();?>css/style.css' />
	<link type='text/css' rel='stylesheet' href='<?php echo $this->getCurrentTemplatePath();?>css/bootstrap.min.css' />
	<script type="text/javascript" src="<?php echo $this->getCurrentTemplatePath();?>js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->getCurrentTemplatePath();?>js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid">
	<div class="header"><?php $this->widgetOutput('logoPosition');?></div>
	<div class='clear'></div>
	<div class="sidebar">
		<?php $this->widgetOutput('sidebarPosition');?>
	</div>
	<div class='content'>
		<?php echo $this->appOutput();?>
	</div>
	<div class='clear'></div>
	<div class="footer">
		2015 &copy; <a class="mylinks" href="http://findalltogether.blogspot.com/p/about.html">Harish Kumar</a> @ <a class="mylinks" href="http://findalltogether.blogspot.com/">Find all together| Web Development</a>			
	</div>
	<style type="text/css">
		.mylinks{
			color:#FFF;
			font-size:28px;
		}
	</style>
</div>
</body>
</html>