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
  $_SESSION['asignment'] = new Assignment($link,$id);
}
if (isset($_SESSION['asignment'])){
  $_SESSION['asignment']->getPreviewHtml();
}

page_footer()
?>