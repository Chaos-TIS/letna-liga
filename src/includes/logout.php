<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();
session_unset();
session_destroy();
session_write_close();
header('Location: ../index.php');
die;
?>