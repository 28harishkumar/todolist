<html>
<head>
	<title>This is default template of MyCMS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link type='text/css' rel='stylesheet' href='<?php echo $this->getCurrentTemplatePath();?>css/style.css' />
	<link type='text/css' rel='stylesheet' href='<?php echo $this->getCurrentTemplatePath();?>css/bootstrap.min.css' />
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
		@ 2015 Harish Kumar			
	</div>
</div>
</body>
</html>