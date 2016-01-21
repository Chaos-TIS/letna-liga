<?php
require_once(dirname(__FILE__)."/includes/functions.php");

page_head("Letná liga FLL");
page_nav();
if (!isset($_SESSION['loggedUser']))
            get_login_form();
        else
            get_logout_button();
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