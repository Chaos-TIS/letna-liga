<?php
header('Content-type: text/plain; charset=utf-8');
require_once(dirname(__FILE__)."/functions.php");
echo show_table($_GET["year"]);
?>