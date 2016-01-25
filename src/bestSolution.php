<?php
require_once(dirname(__FILE__)."/includes/functions.php");

page_head("Letná liga FLL");
page_nav();
get_topright_form();
?>
<div id="content">
<?php

$id = (integer)$_GET["id"] ;
if($link = db_connect()){
  $_SESSION['assignment'] = new Assignment($link,$id);
}
if (isset($_SESSION['assignment'])){
  $_SESSION['assignment']->getBestSolution();
}
if (isset($_POST["save"])){
	if (isset($_POST['best'])){
     $_SESSION['assignment']->addBestSolution($_POST['best']); }}


page_footer()
?>