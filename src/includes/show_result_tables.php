<?php
header('Content-type: text/plain; charset=utf-8');
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();
require_once(dirname(__FILE__)."/functions.php");
echo get_result_table(1, $_SESSION['lang'], $_GET["year"]);
echo get_result_table(0, $_SESSION['lang'], $_GET["year"]);
?>