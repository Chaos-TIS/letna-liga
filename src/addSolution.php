<?php
session_start();
require_once(dirname(__FILE__)."/includes/functions.php");
page_head("Letná liga FLL - Pridanie riešenia");
page_nav();

if (!isset($_SESSION["assignment"]) || $_SESSION["assignment"] == null) die("Nie je vybrane zadanie!");
if (!isset($_SESSION["team"]) || $_SESSION["team"] == null) die("Nie si prihlásený!");

$sql_get_solution = "SELECT c.context_id FROM solutions s, contexts c WHERE s.context_id = c.context_id AND s.assignment_id = ".$_GET["aid"]." AND c.user_id = ".$_SESSION["id"];
$conn = db_connect();
$solution = mysqli_query($conn,$sql_get_solution);

if (mysqli_num_rows($solution) == 0) {
	$cid = new_solution($conn, $_SESSION["team"]->getId(),$_SESSION["assignemnt"]->getId());
}
else {
	$cid = mysqli_fetch_array($solution)['context_id'];
}

$solution = new Solution($conn, $cid, $_SESSION["team"], $_SESSION["assignemnt"]);

if (isset($_POST['textPopis']) && $_POST['textPopis'] != $solution->getTxt()) {
	$solution->setTxt($_POST['textPopis']);	
}

if (isset($_FILES['uploadedImages'])) {
	$fileCount = count($_FILES['uploadedImages']['name']);
	if ($fileCount != 0 && $myFile["name"][0] != "") {
		$solution->uploadImage($conn, $_FILES['uploadedImages']);
	}
}

if (isset($_POST['textVideo']) && $_POST['textVideo'] != "" ) {
	$solution->uploadVideo($conn, $_POST['textVideo']);
}

if (isset($_FILES['uploadedFiles'])) {
	$fileCount = count($_FILES['uploadedFiles']["name"]);
	if ($fileCount != 0 && $_FILES['uploadedFiles']["name"][0] != "") {
		$solution->uploadProgram($conn, $_FILES['uploadedFiles']);
	}
}

mysqli_close($conn);

$solution->getEditingHtml();

<?php
page_footer();
?>