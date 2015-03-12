<?php
if (!ini_get('display_errors')) {
    ini_set('display_errors', '1');
}
$security_check = 1;
require_once('includes/templateFunctions.php');
$tmpl=new TemplateFunctions();
$tmpl->setWidget('logoPosition','logo');
$tmpl->setWidget('sidebarPosition','sidemenu');
$tmpl->show();
?>