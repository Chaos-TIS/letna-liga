<?php
require_once(dirname(__FILE__)."/includes/functions.php");
page_head("Letná liga FLL - Pridanie zadania");
page_nav();

/*
$conn = db_connect();
$_SESSION["loggedUser"] = Organisator::getFromDatabaseByID($conn, 1);
*/

if (!isset($_SESSION["loggedUser"]) || $_SESSION["loggedUser"] == null) die("Nie si prihlásený!");
if (get_class($_SESSION["loggedUser"]) == "Team") die("Iba administrátor alebo rozhodca môže pridávať zadania!");

$conn = db_connect();

if(isset($_GET["cid"]) && !empty($_GET["cid"])) {
	$sql_get_assignment = "SELECT * FROM assignments a, contexts c WHERE c.context_id = a.context_id AND c.context_id = ".$_GET["cid"];	
	if (mysqli_query($conn,$sql_get_assignment)) {
		$assignment = new Assignment($conn, $_GET["cid"]);
		if (!$_SESSION["loggedUser"]->isAdmin() && $_SESSION["loggedUser"]->getId() != $assignment->getId()) {
			die("Nemáš práva k editovaniu tohto riešenia!");
		}
	}
	else {
		die("Toto zadanie neexistuje!");
	}
}
else {
	$uid = new_assignment($conn, $_SESSION["loggedUser"]->getId());
	$assignment = new Assignment($conn, $uid);
}

if (isset($_POST['checkbox'])) {
	$assignment->deleteAttachments($conn, $_POST['checkbox']);
}

if (isset($_POST['skName']) && $_POST['skName'] != $assignment->getSkName()) {
	$assignment->setSkName($conn, $_POST['skName']);	
}

if (isset($_POST['engName']) && $_POST['engName'] != $assignment->getEngName()) {
	$assignment->setEngName($conn, $_POST['engName']);	
}

if (isset($_POST['skTextPopis']) && $_POST['skTextPopis'] != $assignment->getSkTxt()) {
	$assignment->setSkTxt($conn, $_POST['skTextPopis']);	
}

if (isset($_POST['engTextPopis']) && $_POST['engTextPopis'] != $assignment->getEngTxt()) {
	$assignment->setEngTxt($conn, $_POST['engTextPopis']);	
}

if (isset($_POST['textVideo']) && $_POST['textVideo'] != "" ) {
	$assignment->uploadVideo($conn, $_POST['textVideo']);
}

if (isset($_FILES['uploadedFiles'])) {
	$fileCount = count($_FILES['uploadedFiles']["name"]);
	if ($fileCount != 0 && $_FILES['uploadedFiles']["name"][0] != "") {
		$assignment->uploadFiles($conn, $_FILES['uploadedFiles']);
	}
}

$assignment->setAttachments($conn);
mysqli_close($conn);
$assignment->getEditingHtml();

page_footer();
?>