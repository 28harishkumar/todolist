<?php
require_once('includes/templateFunctions.php');
$tmpl=new TemplateFunctions();
$tmpl->setWidget('logoPosition','logo');
$tmpl->setWidget('sidebarPosition','sidemenu');
$tmpl->show();
?>