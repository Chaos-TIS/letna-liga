<?php
header('Content-type: text/plain; charset=utf-8');
require_once(dirname(__FILE__)."/functions.php");
echo show_table(1, $_GET["year"]);
echo show_table(0, $_GET["year"]);
?>